<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return view('client', compact('clients'));
    }

    public function create()
    {
        return view('client-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'ic_passport' => 'required|string|max:50',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address_current' => 'required|string',
            'address_correspondence' => 'nullable|string',
            'tin_no' => 'nullable|string|max:50',
            'job' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'dependent' => 'nullable|integer|min:0',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:30',
            'family_address' => 'nullable|string',
            'agent_banker' => 'nullable|string|max:255',
            'financier_bank' => 'nullable|string|max:255',
            'lawyers_parties' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Client::create($data);

        return redirect()->route('client.index')->with('success', 'Client created successfully');
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('client-show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('client-edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'ic_passport' => 'required|string|max:50',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address_current' => 'required|string',
            'address_correspondence' => 'nullable|string',
            'tin_no' => 'nullable|string|max:50',
            'job' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric',
            'dependent' => 'nullable|integer|min:0',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:30',
            'family_address' => 'nullable|string',
            'agent_banker' => 'nullable|string|max:255',
            'financier_bank' => 'nullable|string|max:255',
            'lawyers_parties' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $client = Client::findOrFail($id);
        $client->update($data);

        return redirect()->route('client.show', $client->id)->with('success', 'Client updated successfully');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect()->route('client.index')->with('success', 'Client deleted successfully');
    }

    public function toggleBan($id)
    {
        $client = Client::findOrFail($id);
        $client->is_banned = !$client->is_banned;
        $client->save();
        return redirect()->back()->with('success', $client->is_banned ? 'Client banned' : 'Client unbanned');
    }
}
