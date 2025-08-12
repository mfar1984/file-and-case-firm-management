<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('created_at', 'desc')->get();
        return view('partner', compact('partners'));
    }

    public function create()
    {
        return view('partner-create');
    }

    public function store(Request $request)
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

        Partner::create($data);

        return redirect()->route('partner.index')->with('success', 'Partner created successfully');
    }

    public function show($id)
    {
        $partner = Partner::findOrFail($id);
        return view('partner-show', compact('partner'));
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('partner-edit', compact('partner'));
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