<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\Client;
use App\Models\Partner;
use App\Models\CaseParty;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isClient = $user->hasRole('Client');
        
        // Get date range based on period filter
        $period = $request->get('period', 'this_month');
        $dateRange = $this->getDateRange($period, $request);
        
        if ($isClient) {
            return $this->clientOverview($user, $dateRange);
        }
        
        return $this->staffOverview($user, $dateRange);
    }
    
    /**
     * Overview for Client role - shows only their cases
     */
    private function clientOverview($user, $dateRange)
    {
        // Get client record linked to this user
        $client = Client::where('user_id', $user->id)->first();
        
        // If no client linked, try to find by email
        if (!$client) {
            $client = Client::where('email', $user->email)->first();
        }
        
        $clientCaseIds = [];
        
        if ($client) {
            // Get cases where this client is a party (by name or IC)
            $clientCaseIds = CaseParty::where(function($query) use ($client, $user) {
                $query->where('name', 'LIKE', '%' . $client->name . '%')
                      ->orWhere('ic_passport', $client->ic_passport)
                      ->orWhere('email', $user->email);
            })->pluck('case_id')->unique()->toArray();
        } else {
            // Fallback: find by user email in case parties
            $clientCaseIds = CaseParty::where('email', $user->email)
                ->pluck('case_id')->unique()->toArray();
        }
        
        // Get cases for this client
        $totalCases = CourtCase::whereIn('id', $clientCaseIds)->count();
        $activeCases = CourtCase::whereIn('id', $clientCaseIds)
            ->where('status', 'active')
            ->count();
        
        // Recent cases
        $recentCases = CourtCase::whereIn('id', $clientCaseIds)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Upcoming hearings for client's cases
        $upcomingHearings = CalendarEvent::whereIn('case_id', $clientCaseIds)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();
        
        return view('overview', [
            'isClient' => true,
            'clientName' => $client ? $client->name : $user->name,
            'totalCases' => $totalCases,
            'activeCases' => $activeCases,
            'pendingCases' => CourtCase::whereIn('id', $clientCaseIds)->where('status', 'pending')->count(),
            'closedCases' => CourtCase::whereIn('id', $clientCaseIds)->where('status', 'closed')->count(),
            'recentCases' => $recentCases,
            'upcomingHearings' => $upcomingHearings,
            'dateRange' => $dateRange,
        ]);
    }
    
    /**
     * Overview for Staff/Admin - shows all firm data
     */
    private function staffOverview($user, $dateRange)
    {
        // Total counts
        $totalCases = CourtCase::count();
        $activeCases = CourtCase::where('status', 'active')->count();
        $totalClients = Client::count();
        $totalPartners = Partner::count();
        
        // Recent cases
        $recentCases = CourtCase::orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Upcoming hearings
        $upcomingHearings = CalendarEvent::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();
        
        return view('overview', [
            'isClient' => false,
            'totalCases' => $totalCases,
            'activeCases' => $activeCases,
            'totalClients' => $totalClients,
            'totalPartners' => $totalPartners,
            'recentCases' => $recentCases,
            'upcomingHearings' => $upcomingHearings,
            'dateRange' => $dateRange,
        ]);
    }
    
    /**
     * Get date range based on period selection
     */
    private function getDateRange($period, $request)
    {
        switch ($period) {
            case 'last_month':
                return [
                    'start' => Carbon::now()->subMonth()->startOfMonth(),
                    'end' => Carbon::now()->subMonth()->endOfMonth(),
                ];
            case 'last_3_months':
                return [
                    'start' => Carbon::now()->subMonths(3)->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth(),
                ];
            case 'last_6_months':
                return [
                    'start' => Carbon::now()->subMonths(6)->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth(),
                ];
            case 'this_year':
                return [
                    'start' => Carbon::now()->startOfYear(),
                    'end' => Carbon::now()->endOfYear(),
                ];
            case 'custom':
                return [
                    'start' => $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth(),
                    'end' => $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth(),
                ];
            default: // this_month
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth(),
                ];
        }
    }
}
