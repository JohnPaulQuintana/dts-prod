<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Events\NotifyEvent;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //events push
    public function pushEvents(Request $request){
        // dd($request);
        $events = Event::create([
            'title' => $request->title,
            'time' => $request->time,
            'event_notes' => $request->note,
            'start' => $request->start,
            'end' => null,
            'classname' => $request->className,
            'url' => null,
        ]);

         // Build the success message
         $message = 'Successfully added new events';

         // Prepare the toast notification data
         $notification = [
             'status' => 'success',
             'message' => $message,
         ];
 
         // Convert the notification to JSON
         $notificationJson = json_encode($notification);
         event(new NotifyEvent('departments sending a documents'));
         // Redirect back with a success message and the inserted products
         return response()->json(['notification'=>$notificationJson], 200);
    }
    //events delete
    public function deleteEvents(Request $request){
        // dd($request);
        // Retrieve the event ID from the request
        $eventId = $request->input('event_id');
        Event::where('id',$eventId)->delete();
         // Build the success message
         $message = 'Successfully deleted new events';

         // Prepare the toast notification data
         $notification = [
             'status' => 'success',
             'message' => $message,
         ];
 
         // Convert the notification to JSON
         $notificationJson = json_encode($notification);
         event(new NotifyEvent('departments sending a documents'));
         // Redirect back with a success message and the inserted products
         return response()->json(['notification'=>$notificationJson], 200);
    }

    public function fetchEvents(Request $request)
    {
        // Fetch events from the database, for example:
        $events = Event::orderBy('created_at', 'desc')->get();

        // Transform the events into the format needed for FullCalendar
        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvent = [
                'id' => $event->id,
                'title' => $event->title,
                'time' => $event->time,
                'notes' => $event->event_notes,
                'start' => $event->start,
                'end' => $event->end,
                'className' => $event->classname,
                // Add other event properties as needed
            ];

            $formattedEvents[] = $formattedEvent;
        }

        return response()->json($formattedEvents, 200);
    }

    public function EditEvents(Request $request){
        // dd($request->input('start'));
        // Find the event based on the content (assuming you have a unique content for each event)
        $event = Event::where('id', $request->input('event_id'))->first();
        if($event){
            $event->title = $request->input('title');
            $event->time = $request->input('time');
            $event->event_notes = $request->input('notes');
            $event->classname = $request->input('category');
            if($request->input('start') !== null){
                $event->start = $request->input('start');
            }

            $event->save();
            // Build the success message
            $message = 'Successfully edited events';

            // Prepare the toast notification data
            $notification = [
                'status' => 'success',
                'message' => $message,
            ];
    
            // Convert the notification to JSON
            $notificationJson = json_encode($notification);
            event(new NotifyEvent('departments sending a documents'));
            // Redirect back with a success message and the inserted products
            return back()->with(['notification'=>$notificationJson]);
        }
    }

    public function updateEvents(Request $request){
        // dd($request);
         // Get the start and content values from the request
        $start = $request->input('start');
        $content = $request->input('content');
        
        // Find the event based on the content (assuming you have a unique content for each event)
        $event = Event::where('title', $content)->first();

        // Create a Carbon instance from the original date string
        $originalStartDate = Carbon::parse($start);

        // Format the date as "year, month, day"
        $formattedStartDate = $originalStartDate->format('Y-m-d');

        // Check if the event was found
        if ($event) {
            // Update the event's title with the content value
            $event->title = $content;
            $event->start = $formattedStartDate;
            $event->save();

            // Build the success message
            $message = 'Successfully moved events';

            // Prepare the toast notification data
            $notification = [
                'status' => 'success',
                'message' => $message,
            ];
    
            // Convert the notification to JSON
            $notificationJson = json_encode($notification);
            event(new NotifyEvent('departments sending a documents'));
            // Redirect back with a success message and the inserted products
            return response()->json(['notification'=>$notificationJson], 200);
        } else {
            return response()->json(['error' => 'Event not found'], 404);
        }
    }
}
