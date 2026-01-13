<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAddress;
use App\Models\Firm;
use App\Services\UserCreationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        // Get clients with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see all clients or filter by session firm
            if (session('current_firm_id')) {
                $clients = Client::forFirm(session('current_firm_id'))
                    ->with(['addresses', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $clients = Client::withoutFirmScope()
                    ->with(['addresses', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        } else {
            // Regular users see only their firm's clients (HasFirmScope trait handles this)
            $clients = Client::with(['addresses', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('client', compact('clients'));
    }

    public function create()
    {
        // Get all firms for Super Administrator, current firm for others
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $firms = Firm::where('id', $firmId)->get();
        }

        return view('client-create', compact('firms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'party_type' => 'required|in:applicant,respondent,third_party,witness',
            'identity_type' => 'required|string|max:255',
            'identity_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'fax' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'addresses' => 'required|array|min:1',
            'addresses.*.address_line1' => 'nullable|string|max:255',
            'addresses.*.address_line2' => 'nullable|string|max:255',
            'addresses.*.address_line3' => 'nullable|string|max:255',
            'addresses.*.postcode' => 'nullable|string|max:20',
            'addresses.*.city' => 'nullable|string|max:255',
            'addresses.*.state' => 'nullable|string|max:255',
            'addresses.*.country' => 'nullable|string|max:255',
            'tin_no' => 'nullable|string|max:255',
            'job' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'dependent' => 'nullable|integer|min:0',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:50',
            'family_address' => 'nullable|string',
            'agent_banker' => 'nullable|string|max:255',
            'financier_bank' => 'nullable|string|max:255',
            'lawyers_parties' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Prepare client data
        $clientData = [
            'party_type' => $data['party_type'],
            'identity_type' => $data['identity_type'],
            'ic_passport' => $data['identity_number'],
            'name' => $data['name'],
            'gender' => $data['gender'],
            'nationality' => $data['nationality'],
            'race' => $data['race'],
            'phone' => $data['phone'],
            'fax' => $data['fax'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'tin_no' => $data['tin_no'],
            'job' => $data['job'],
            'salary' => $data['salary'],
            'dependent' => $data['dependent'] ?? 0,
            'family_contact_name' => $data['family_contact_name'],
            'family_contact_phone' => $data['family_contact_phone'],
            'family_address' => $data['family_address'],
            'agent_banker' => $data['agent_banker'],
            'financier_bank' => $data['financier_bank'],
            'lawyers_parties' => $data['lawyers_parties'],
            'notes' => $data['notes'],
        ];

        // Handle numeric fields - ensure proper type casting
        $clientData['salary'] = !empty($clientData['salary']) ? (float)$clientData['salary'] : null;
        $clientData['dependent'] = !empty($clientData['dependent']) ? (int)$clientData['dependent'] : 0;
        
        // Convert remaining null values to empty strings for string fields
        foreach ($clientData as $key => $value) {
            if (!in_array($key, ['salary', 'dependent']) && $value === null) {
                $clientData[$key] = '';
            }
        }

        // Auto-create user account
        $userResult = UserCreationService::createUserForClient($clientData);
        $clientData['user_id'] = $userResult['user']->id;

        // Add firm context
        $user = auth()->user();
        if ($user->hasRole('Super Administrator') && $request->has('firm_id')) {
            $clientData['firm_id'] = $request->firm_id;
        } else {
            $clientData['firm_id'] = session('current_firm_id') ?? $user->firm_id;
        }

        // Create client
        $client = Client::create($clientData);

        // Create addresses
        foreach ($data['addresses'] as $index => $addressData) {
            ClientAddress::create([
                'client_id' => $client->id,
                'address_line1' => $addressData['address_line1'] ?? '',
                'address_line2' => $addressData['address_line2'] ?? '',
                'address_line3' => $addressData['address_line3'] ?? '',
                'postcode' => $addressData['postcode'] ?? '',
                'city' => $addressData['city'] ?? '',
                'state' => $addressData['state'] ?? '',
                'country' => $addressData['country'] ?? 'Malaysia',
                'is_primary' => $index === 0, // First address is primary
            ]);
        }

        $message = 'Client created successfully.';
        if (!empty($clientData['email'])) {
            if (\App\Services\EmailConfigurationService::isEmailConfigured()) {
                $emailSettings = \App\Services\EmailConfigurationService::getEmailSettings();
                if ($emailSettings->notify_user_accounts) {
                    $message .= ' Login credentials have been sent to ' . $clientData['email'] . '. Email verification link has also been sent.';
                } else {
                    $message .= ' User account created with username: ' . $userResult['username'] . ' and password: ' . $userResult['password'] . ' (Email notifications disabled). Email verification required.';
                }
            } else {
                $message .= ' User account created with username: ' . $userResult['username'] . ' and password: ' . $userResult['password'] . ' (Email not configured). Email verification required.';
            }
        } else {
            $message .= ' User account created with username: ' . $userResult['username'] . ' and password: ' . $userResult['password'] . '. Email verification required.';
        }

        // Log client creation
        activity()
            ->performedOn($client)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'client_name' => $client->name,
                'ic_passport' => $client->ic_passport,
                'email' => $client->email
            ])
            ->log("Client {$client->name} created");

        return redirect()->route('client.index')->with('success', $message);
    }

    public function show($id)
    {
        // Find client with firm scope validation
        $user = auth()->user();
        $currentFirmId = session('current_firm_id');

        if ($user->hasRole('Super Administrator') && $currentFirmId) {
            // Super Admin with firm context - respect firm scope
            $client = Client::forFirm($currentFirmId)
                ->with(['addresses', 'user'])
                ->findOrFail($id);
        } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
            // Super Admin without firm context - can access any client (for system management)
            $client = Client::withoutFirmScope()
                ->with(['addresses', 'user'])
                ->findOrFail($id);
        } else {
            // Regular users can only access clients from their firm (HasFirmScope trait handles this)
            $client = Client::with(['addresses', 'user'])->findOrFail($id);
        }

        return view('client-show', compact('client'));
    }

    public function edit($id)
    {
        // Find client with firm scope validation
        $user = auth()->user();
        $currentFirmId = session('current_firm_id');

        if ($user->hasRole('Super Administrator') && $currentFirmId) {
            // Super Admin with firm context - respect firm scope
            $client = Client::forFirm($currentFirmId)
                ->with(['addresses', 'user'])
                ->findOrFail($id);
            $firms = Firm::where('id', $currentFirmId)->get();
        } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
            // Super Admin without firm context - can edit any client (for system management)
            $client = Client::withoutFirmScope()
                ->with(['addresses', 'user'])
                ->findOrFail($id);
            $firms = Firm::orderBy('name')->get();
        } else {
            // Regular users can only edit clients from their firm (HasFirmScope trait handles this)
            $client = Client::with(['addresses', 'user'])->findOrFail($id);
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $firms = Firm::where('id', $firmId)->get();
        }

        return view('client-edit', compact('client', 'firms'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'party_type' => 'required|in:applicant,respondent',
            'identity_type' => 'required|string|max:255',
            'identity_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,not_specified',
            'nationality' => 'required|string|max:100',
            'race' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'fax' => 'nullable|string|max:30',
            'mobile' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'addresses' => 'required|array|min:1',
            'addresses.*.address_line1' => 'nullable|string|max:255',
            'addresses.*.address_line2' => 'nullable|string|max:255',
            'addresses.*.address_line3' => 'nullable|string|max:255',
            'addresses.*.postcode' => 'nullable|string|max:20',
            'addresses.*.city' => 'nullable|string|max:100',
            'addresses.*.state' => 'required|string|max:100',
            'addresses.*.country' => 'required|string|max:100',
            'tin_no' => 'nullable|string|max:50',
            'job' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'dependent' => 'nullable|integer|min:0',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:30',
            'family_address' => 'nullable|string',
            'agent_banker' => 'nullable|string|max:255',
            'financier_bank' => 'nullable|string|max:255',
            'lawyers_parties' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Process addresses
        $addresses = $request->input('addresses', []);
        
        // Update client data
        $clientData = [
            'name' => $data['name'],
            'ic_passport' => $data['identity_number'], // Map identity_number to ic_passport
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'],
            'tin_no' => $data['tin_no'],
            'job' => $data['job'],
            'salary' => $data['salary'],
            'dependent' => $data['dependent'] ?? 0, // Set default to 0 if null
            'family_contact_name' => $data['family_contact_name'],
            'family_contact_phone' => $data['family_contact_phone'],
            'family_address' => $data['family_address'],
            'agent_banker' => $data['agent_banker'],
            'financier_bank' => $data['financier_bank'],
            'lawyers_parties' => $data['lawyers_parties'],
            'notes' => $data['notes'],
            // New fields
            'party_type' => $data['party_type'],
            'identity_type' => $data['identity_type'],
            'gender' => $data['gender'],
            'nationality' => $data['nationality'],
            'race' => $data['race'],
            'fax' => $data['fax'],
            'mobile' => $data['mobile'],
        ];

        // Handle numeric fields - ensure proper type casting
        $clientData['salary'] = !empty($clientData['salary']) ? (float)$clientData['salary'] : null;
        $clientData['dependent'] = !empty($clientData['dependent']) ? (int)$clientData['dependent'] : 0;
        
        // Convert remaining null values to empty strings for string fields
        foreach ($clientData as $key => $value) {
            if (!in_array($key, ['salary', 'dependent']) && $value === null) {
                $clientData[$key] = '';
            }
        }

        // Add firm context
        $user = auth()->user();
        if ($user->hasRole('Super Administrator') && $request->has('firm_id')) {
            $clientData['firm_id'] = $request->firm_id;
        } else {
            $clientData['firm_id'] = session('current_firm_id') ?? $user->firm_id;
        }

        $client = Client::findOrFail($id);
        $client->update($clientData);

        // Delete existing addresses and create new ones
        $client->addresses()->delete();
        
        // Create new addresses
        if (!empty($addresses)) {
            foreach ($addresses as $index => $address) {
                if (!empty($address['state']) && !empty($address['country'])) {
                    $clientAddress = new \App\Models\ClientAddress();
                    $clientAddress->client_id = $client->id;
                    $clientAddress->address_line1 = $address['address_line1'] ?? null;
                    $clientAddress->address_line2 = $address['address_line2'] ?? null;
                    $clientAddress->address_line3 = $address['address_line3'] ?? null;
                    $clientAddress->postcode = $address['postcode'] ?? null;
                    $clientAddress->city = $address['city'] ?? null;
                    $clientAddress->state = $address['state'];
                    $clientAddress->country = $address['country'];
                    $clientAddress->is_primary = ($index === 0); // First address is primary
                    $clientAddress->save();
                }
            }
        }

        // Log client update
        activity()
            ->performedOn($client)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'client_name' => $client->name,
                'ic_passport' => $client->ic_passport,
                'email' => $client->email
            ])
            ->log("Client {$client->name} updated");

        return redirect()->route('client.show', $client->id)->with('success', 'Client updated successfully');
    }

    public function destroy($id)
    {
        // Find client with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can delete any client
            $client = Client::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only delete clients from their firm (HasFirmScope trait handles this)
            $client = Client::findOrFail($id);
        }

        // Log client deletion before deleting
        activity()
            ->performedOn($client)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'client_name' => $client->name,
                'ic_passport' => $client->ic_passport,
                'email' => $client->email,
                'firm_id' => $client->firm_id
            ])
            ->log("Client {$client->name} deleted");

        $client->delete();
        return redirect()->route('client.index')->with('success', 'Client deleted successfully');
    }

    public function toggleBan($id)
    {
        // Find client with firm scope validation
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can toggle ban for any client
            $client = Client::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only toggle ban for clients from their firm (HasFirmScope trait handles this)
            $client = Client::findOrFail($id);
        }

        $client->is_banned = !$client->is_banned;
        $client->save();

        // Log the action
        activity()
            ->performedOn($client)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'client_name' => $client->name,
                'action' => $client->is_banned ? 'banned' : 'unbanned',
                'firm_id' => $client->firm_id
            ])
            ->log("Client {$client->name} " . ($client->is_banned ? 'banned' : 'unbanned'));

        return redirect()->back()->with('success', $client->is_banned ? 'Client banned' : 'Client unbanned');
    }
}
