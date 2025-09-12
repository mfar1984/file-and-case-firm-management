<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class FirmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only Super Administrator can access firm management
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        $firms = Firm::orderBy('name')->get();

        return view('settings.firms.index', compact('firms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only Super Administrator can create firms
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        return view('settings.firms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only Super Administrator can create firms
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('firm-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $firm = Firm::create($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($firm)
            ->withProperties(['firm_name' => $firm->name])
            ->log('Created new firm');

        return redirect()->route('settings.firms.index')
            ->with('success', 'Firm created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Firm $firm)
    {
        // Only Super Administrator can view firm details
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        return view('settings.firms.show', compact('firm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Firm $firm)
    {
        // Only Super Administrator can edit firms
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        return view('settings.firms.edit', compact('firm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Firm $firm)
    {
        // Only Super Administrator can update firms
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($firm->logo) {
                Storage::disk('public')->delete($firm->logo);
            }

            $logoPath = $request->file('logo')->store('firm-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $firm->update($validated);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($firm)
            ->withProperties(['firm_name' => $firm->name])
            ->log('Updated firm information');

        return redirect()->route('settings.firms.index')
            ->with('success', 'Firm updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Firm $firm)
    {
        // Only Super Administrator can delete firms
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm management.');
        }

        // Prevent deletion if firm has users
        if ($firm->users()->count() > 0) {
            return redirect()->route('settings.firms.index')
                ->with('error', 'Cannot delete firm with existing users.');
        }

        // Delete logo if exists
        if ($firm->logo) {
            Storage::disk('public')->delete($firm->logo);
        }

        // Log activity before deletion
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['firm_name' => $firm->name])
            ->log('Deleted firm');

        $firm->delete();

        return redirect()->route('settings.firms.index')
            ->with('success', 'Firm deleted successfully.');
    }

    /**
     * Switch to a different firm (Super Administrator only)
     */
    public function switchFirm(Request $request)
    {
        // Only Super Administrator can switch firms
        if (!auth()->user()->hasRole('Super Administrator')) {
            abort(403, 'Unauthorized access to firm switching.');
        }

        $validated = $request->validate([
            'firm_id' => 'required|exists:firms,id',
        ]);

        $firm = Firm::findOrFail($validated['firm_id']);

        // Set the new firm context
        session(['current_firm_id' => $firm->id]);
        setPermissionsTeamId($firm->id);

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'firm_name' => $firm->name,
                'firm_id' => $firm->id
            ])
            ->log('Switched to firm: ' . $firm->name);

        return redirect()->back()
            ->with('success', 'Switched to ' . $firm->name . ' successfully.');
    }
}
