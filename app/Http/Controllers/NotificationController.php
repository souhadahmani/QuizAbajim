<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $notifications = auth()->user()->notifications()
                ->latest()
                ->take(10);
            return view('Notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            Log::error('Error in notifications index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'فشل في تحميل الإشعارات');
        }
    }

    public function getNotifications(Request $request)
    {
        try {
            $notifications = auth()->user()->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->data['title'] ?? $notification->title,
                        'message' => $notification->data['message'] ?? $notification->message,
                        'type' => $notification->data['type'] ?? 'info',
                        'created_at' => $notification->created_at->format('Y M d | H:i'),
                        
                        'read' => $notification->read_at ? true : false,
                    ];
                });

            return response()->json(['notifications' => $notifications]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'فشل في جلب الإشعارات'], 500);
        }
    }
    public function getUnreadCount()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                Log::warning('No authenticated user in getUnreadCount');
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $count = $user->notifications()
                ->whereNull('read_at')
                ->count();
            Log::info('Unread count fetched', ['count' => $count]);
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            Log::error('Error fetching unread count:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'فشل في جلب عدد الإشعارات غير المقروءة'], 500);
        }
    }    public function markAsRead($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read:', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'فشل في تحديد الإشعار كمقروء'], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            auth()->user()->notifications()
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking all as read:', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'فشل في تحديد جميع الإشعارات كمقروءة'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting notification:', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'فشل في حذف الإشعار'], 500);
        }
    }

    public function view($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            $url = $notification->data['url'] ?? '/dashboard'; // Customize URL based on notification
            return response()->json(['success' => true, 'url' => $url]);
        } catch (\Exception $e) {
            Log::error('Error viewing notification:', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'فشل في عرض الإشعار'], 500);
        }
    }
}