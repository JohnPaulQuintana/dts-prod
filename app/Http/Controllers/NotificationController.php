<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotification(){
        $notifications = DB::table('notifications')
            ->select('notifications.*')
            ->where('notifications.notification_to_id', Auth::user()->id )
            ->where('notifications.notification_status', 'unread' )
            ->orderBy('notifications.created_at', 'desc')
            ->get();

            // Format the time using Carbon
            foreach ($notifications as $notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
            }

            return response()->json(['notifications'=>$notifications]);
    }
    
}
