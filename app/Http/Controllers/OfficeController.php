<?php

namespace App\Http\Controllers;

use App\Helpers\TextAbbreviator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class OfficeController extends Controller
{
    public function showOffices(){
        $offices = Office::where('status', 'active')
            ->where('office_type','!=','Admin')
            ->get();
    
        return view('admin.components.contents.office')->with(['offices'=>$offices]);
    }
    public function showDepartment(){
        $offices = Office::where('status', 'active')
            ->where('office_type','!=','Admin')
            ->get();
    
        return view('departments.components.contents.department')->with(['offices'=>$offices]);
    }

    public function addOffices(Request $request){
        // dd($request);

        // Validate the incoming data
        $validatedOffice = $request->validate([
            'office_name' => ['required', 'string', 'max:255'],
            'office_desc' => ['required', 'string'],
            'office_head' => ['required', 'string', 'max:255'],
            'office_type' => ['required', 'string', Rule::in(['viewing'])], // Adjust the valid types
        ]);

        // Create a new Office instance
        $office = new Office();

        // Assign the validated request data to the Office attributes
        $office->office_name = $validatedOffice['office_name'];
        // helper function for abbrev
        $office->office_abbrev = TextAbbreviator::abbreviate($validatedOffice['office_name']);
        $office->office_description = $validatedOffice['office_desc'];
        $office->office_head = $validatedOffice['office_head'];
        $office->office_type = $validatedOffice['office_type'];
        $office->status = 'active';

        // Save the Office instance to the database
        $office->save();

         // Build the success message
        $message = 'Successfully added new office';

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);
    }

    public function showOfficesUser($office_id){
        // Find the office by its ID
        $users = User::where('office_id',$office_id)->orderBy('name', 'asc')->get();

        if (!$users) {
            // Handle the case where the office is not found
            return abort(404);
        }

        // Format created_at to days, months, years
        foreach ($users as $user) {
            $user->created_at_formatted = Carbon::parse($user->created_at)->format('l, d F Y, H:m A');
        }
        
        // Retrieve users associated with this office
        // $users = $users->users;

        return view('admin.components.contents.users')->with(['users'=>$users]);
    }

    public function addUsers(Request $request){
        // dd($request);
        $request->validate([
            'office_id' => ['required', 'numeric'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->first_name.' '.$request->last_name.' '.$request->middle_name,
            'email' => $request->email,
            'username' => $request->username,
            'office_id' => $request->office_id,
            'status' => 'deactivated',
            'role' => 0,
            'type' => $request->type,
            'password' => Hash::make($request->password),
        ]);

        // Build the success message
        $message = 'Successfully added '.$user->name;

        // Prepare the toast notification data
        $notification = [
            'status' => 'success',
            'message' => $message,
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);

        // Redirect back with a success message and the inserted products
        return back()->with('notification', $notificationJson);
    }
}
