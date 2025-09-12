<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemSetting;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('settings.log');
    }

    public function getLogs(Request $request)
    {
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $query = Activity::with(['causer', 'subject'])
            ->where('firm_id', $firmId)
            ->latest();

        // Filter by level if provided
        if ($request->has('level') && $request->level !== 'all' && !empty($request->level)) {
            $query->where('properties->level', $request->level);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%")
                  ->orWhereHas('causer', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Get all activities for client-side pagination
        $activities = $query->limit(1000)->get();

        // Get system settings for date/time formatting
        $systemSettings = SystemSetting::getSystemSettings();
        $dateFormat = $systemSettings['date_format'] ?? 'Y-m-d';
        $timeFormat = $systemSettings['time_format'] ?? 'H:i:s';
        $timezone = $systemSettings['time_zone'] ?? config('app.timezone', 'UTC');

        $logs = $activities->map(function ($activity) use ($dateFormat, $timeFormat, $timezone) {
            // Convert to system timezone and format
            $datetime = Carbon::parse($activity->created_at)
                ->setTimezone($timezone)
                ->format($dateFormat . ' ' . $timeFormat);

            return [
                'id' => $activity->id,
                'datetime' => $datetime,
                'user' => $activity->causer ? $activity->causer->name : 'System',
                'action' => $this->getActionFromDescription($activity->description),
                'description' => $activity->description,
                'level' => $this->getLevelFromActivity($activity),
                'ip_address' => $activity->properties['ip'] ?? request()->ip(),
                'subject_type' => $activity->subject_type,
                'subject_id' => $activity->subject_id,
            ];
        });

        return response()->json([
            'logs' => $logs,
            'total' => $logs->count()
        ]);
    }

    public function clearLogs()
    {
        try {
            // Only allow admins to clear logs
            if (!Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to clear logs'
                ], 403);
            }

            $user = Auth::user();
            $firmId = session('current_firm_id') ?? $user->firm_id;

            // Only clear logs for current firm
            Activity::where('firm_id', $firmId)->delete();

            // Log the clear action
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['ip' => request()->ip()])
                ->log('Activity logs cleared by admin');

            return response()->json([
                'success' => true,
                'message' => 'All activity logs have been cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear logs: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getActionFromDescription($description)
    {
        if (str_contains($description, 'created')) return 'Created';
        if (str_contains($description, 'updated')) return 'Updated';
        if (str_contains($description, 'deleted')) return 'Deleted';
        if (str_contains($description, 'login')) return 'Login';
        if (str_contains($description, 'logout')) return 'Logout';
        return 'Action';
    }

    private function getLevelFromActivity($activity)
    {
        $description = strtolower($activity->description);
        
        if (str_contains($description, 'deleted') || str_contains($description, 'failed')) {
            return 'error';
        }
        
        if (str_contains($description, 'login') || str_contains($description, 'logout')) {
            return 'warning';
        }
        
        return 'info';
    }
}
