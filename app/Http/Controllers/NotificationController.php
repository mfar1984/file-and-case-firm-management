<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get user's notifications for the bell dropdown (JSON for AJAX; redirect for direct visits)
     */
    public function index(Request $request)
    {
        // If the request is a direct page visit (expects HTML), send the user to the dashboard.
        // This prevents the browser from showing raw JSON and avoids storing this URL as intended.
        if (!$request->expectsJson() && !($request->header('X-Requested-With') === 'XMLHttpRequest')) {
            return redirect()->route('dashboard');
        }

        $user = auth()->user();

        // Apply firm scope filtering for notifications
        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see notifications from current session firm or all firms
            if (session('current_firm_id')) {
                $notificationQuery = Notification::forFirm(session('current_firm_id'))
                    ->where('user_id', $user->id);
                $unreadQuery = Notification::forFirm(session('current_firm_id'))
                    ->where('user_id', $user->id);
            } else {
                // Super Admin hasn't selected a firm - default to their own firm
                $defaultFirmId = $user->firm_id;
                if ($defaultFirmId) {
                    // Set session to user's default firm for consistency
                    session(['current_firm_id' => $defaultFirmId]);

                    $notificationQuery = Notification::forFirm($defaultFirmId)
                        ->where('user_id', $user->id);
                    $unreadQuery = Notification::forFirm($defaultFirmId)
                        ->where('user_id', $user->id);
                } else {
                    // No firm context available - show no notifications
                    $notificationQuery = Notification::whereRaw('1 = 0');
                    $unreadQuery = Notification::whereRaw('1 = 0');
                }
            }
        } else {
            // Regular users see only their firm's notifications (HasFirmScope trait handles this)
            $notificationQuery = Notification::where('user_id', $user->id);
            $unreadQuery = Notification::where('user_id', $user->id);
        }

        // Get recent notifications (last 30 days)
        $notifications = $notificationQuery
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon ?? 'notifications',
                    'url' => $notification->url ?? '#',
                    'time' => $notification->time,
                    'read_at' => $notification->read_at,
                    'type' => $notification->type,
                ];
            });

        // Get unread count
        $unreadCount = $unreadQuery->unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        $user = auth()->user();

        // Ensure user can only mark their own notifications and firm scope validation
        if ($notification->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Additional firm scope validation for Super Admin
        if ($user->hasRole('Super Administrator') && session('current_firm_id')) {
            if ($notification->firm_id !== session('current_firm_id')) {
                return response()->json(['error' => 'Unauthorized - Firm scope mismatch'], 403);
            }
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Apply firm scope filtering for mark all as read
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                Notification::forFirm(session('current_firm_id'))
                    ->where('user_id', $user->id)
                    ->unread()
                    ->update(['read_at' => now()]);
            } else {
                // Super Admin hasn't selected a firm - default to their own firm
                $defaultFirmId = $user->firm_id;
                if ($defaultFirmId) {
                    // Set session to user's default firm for consistency
                    session(['current_firm_id' => $defaultFirmId]);

                    Notification::forFirm($defaultFirmId)
                        ->where('user_id', $user->id)
                        ->unread()
                        ->update(['read_at' => now()]);
                }
                // If no firm context, do nothing
            }
        } else {
            // Regular users (HasFirmScope trait handles filtering)
            Notification::where('user_id', $user->id)
                ->unread()
                ->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $user = auth()->user();

        // Ensure user can only delete their own notifications and firm scope validation
        if ($notification->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Additional firm scope validation for Super Admin
        if ($user->hasRole('Super Administrator') && session('current_firm_id')) {
            if ($notification->firm_id !== session('current_firm_id')) {
                return response()->json(['error' => 'Unauthorized - Firm scope mismatch'], 403);
            }
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get notification statistics
     */
    public function stats(): JsonResponse
    {
        $user = auth()->user();

        // Apply firm scope filtering for statistics
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $baseQuery = Notification::forFirm(session('current_firm_id'))->where('user_id', $user->id);
            } else {
                $baseQuery = Notification::withoutFirmScope()->where('user_id', $user->id);
            }
        } else {
            // Regular users (HasFirmScope trait handles filtering)
            $baseQuery = Notification::where('user_id', $user->id);
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'unread' => (clone $baseQuery)->unread()->count(),
            'today' => (clone $baseQuery)->whereDate('created_at', today())->count(),
            'this_week' => (clone $baseQuery)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return response()->json($stats);
    }
}
