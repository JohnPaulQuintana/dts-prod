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

    // update notification
    public function updateNotification(Request $request){
        // dd($request);
        $updateNotif = Notification::where('notification_from_id',$request->input('id'))->update(['notification_status' =>'read']);
        // return redirect()->route('administrator.dashboard.incoming.request')->with(['search_id'=>$request->input('id')]);
        return response()->json(['notification'=>'success'],200);
    }
    
}
