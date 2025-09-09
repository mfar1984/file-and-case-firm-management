<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Specialization;
use App\Services\UserCreationService;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with('user')->orderBy('created_at', 'desc')->get();
        return view('partner', compact('partners'));
    }

    public function create()
    {
        $specializations = Specialization::where('status', 'active')->orderBy('specialist_name')->get();
        return view('partner-create', compact('specializations'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'firm_name' => 'required|string|max:255',
                'address' => 'required|string',
                'contact_no' => 'required|string|max:50',
                'email' => 'nullable|email|max:255',
                'incharge_name' => 'required|string|max:255',
                'incharge_contact' => 'required|string|max:50',
                'incharge_email' => 'nullable|email|max:255',
                'status' => 'required|in:active,inactive,suspended',
                'specialization' => 'nullable|string|max:100',
                'years_of_experience' => 'nullable|integer|min:0|max:80',
                'bar_council_number' => 'nullable|string|max:50',
                'registration_date' => 'nullable|date',
                'notes' => 'nullable|string',
            ]);

            // Auto-create user account
            $userResult = UserCreationService::createUserForPartner($data);
            $data['user_id'] = $userResult['user']->id;

            $partner = Partner::create($data);

            // Log partner creation
            activity()
                ->performedOn($partner)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'firm_name' => $partner->firm_name,
                    'incharge_name' => $partner->incharge_name,
                    'email' => $partner->email
                ])
                ->log("Partner {$partner->firm_name} created");

            $message = 'Partner created successfully.';
            if (!empty($data['incharge_email'])) {
                if (\App\Services\EmailConfigurationService::isEmailConfigured()) {
                    $emailSettings = \App\Services\EmailConfigurationService::getEmailSettings();
                    if ($emailSettings->notify_user_accounts) {
                        $message .= ' Login credentials have been sent to ' . $data['incharge_email'] . '. Email verification link has also been sent.';
                    } else {
                        $message .= ' User account created with username: ' . $userResult['username'] . ' and password: ' . $userResult['password'] . ' (Email notifications disabled). Email verification required.';
                    }
                } else {
                    $message .= ' User account created with username: ' . $userResult['username'] . ' and password: ' . $userResult['password'] . ' (Email not configured). Email verification required.';
                }
            } else {
                $message .= ' User account created with username: ' . $userResult['username'] . ' and password: ' . $userResult['password'] . '. Email verification required.';
            }
            
            // Force redirect with JavaScript as backup
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <title>Partner Created Successfully</title>
                <meta http-equiv="refresh" content="0;url=/partner">
            </head>
            <body>
                <script>
                    alert("' . $message . '");
                    window.location.href = "/partner";
                </script>
                <p>Partner created successfully! <a href="/partner">Click here if not redirected automatically.</a></p>
            </body>
            </html>';
            
            return response($html);
        } catch (\Exception $e) {
            \Log::error('Partner creation error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create partner: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $partner = Partner::with('user')->findOrFail($id);
        return view('partner-show', compact('partner'));
    }

    public function edit($id)
    {
        $partner = Partner::with('user')->findOrFail($id);
        $specializations = Specialization::where('status', 'active')->orderBy('specialist_name')->get();
        return view('partner-edit', compact('partner', 'specializations'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'firm_name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_no' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'incharge_name' => 'required|string|max:255',
            'incharge_contact' => 'required|string|max:50',
            'incharge_email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive,suspended',
            'specialization' => 'nullable|string|max:100',
            'years_of_experience' => 'nullable|integer|min:0|max:80',
            'bar_council_number' => 'nullable|string|max:50',
            'registration_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $partner = Partner::findOrFail($id);
        $partner->update($data);

        return redirect()->route('partner.show', $partner->id)->with('success', 'Partner updated successfully');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        // Log partner deletion before deleting
        activity()
            ->performedOn($partner)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'firm_name' => $partner->firm_name,
                'incharge_name' => $partner->incharge_name,
                'email' => $partner->email
            ])
            ->log("Partner {$partner->firm_name} deleted");

        $partner->delete();
        return redirect()->route('partner.index')->with('success', 'Partner deleted successfully');
    }

    public function toggleBan($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->is_banned = !$partner->is_banned;
        $partner->save();
        return redirect()->back()->with('success', $partner->is_banned ? 'Partner banned' : 'Partner unbanned');
    }
} 