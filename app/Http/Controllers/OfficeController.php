<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\TextAbbreviator;
// use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
class OfficeController extends Controller
{
    public function showOffices(){
        $offices = Office::where('status', 'active')
            ->where('office_type','!=','Administrator')
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
            'office_type' => ['required', 'string'], // Rule::in(['viewing'])], // Adjust the valid types
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
        $currentOffice = Office::where('id',$office_id)->get();

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

        return view('admin.components.contents.users')->with(['users'=>$users,'currentOffice'=>$currentOffice]);
    }

    public function addUsers(Request $request){
        // dd($request);
        try {
            $request->validate([
                'office_id' => ['required', 'numeric'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', 'min:8', 'regex:/[!@#$%^&*(),.?":{}|<>]/'],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();

            $notification = [
                'status' => 'error',
                'fields' => $errors,
                'message' => 'All field is required!',
            ];

            // Redirect back with a success message and the inserted products
            return response()->json($notification);
        }

        $user = User::create([
            'name' => $request->first_name.' '.$request->middle_name.' '.$request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'office_id' => $request->office_id,
            'status' => 'deactivated',
            'role' => 0,
            'assigned' => $request->type,
            'password' => Hash::make($request->password),
        ]);

        // Send email verification notification
        event(new Registered($user));

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

    public function destroy(Request $request){
       // 1. Retrieve the office by its ID
        $office_id = $request->input('id');
        $office = Office::find($office_id);

        // 2. Check if the office exists
        if (!$office) {
            // Handle the case where the office doesn't exist
            return response()->json(['message' => 'Office not found'], 404);
        }

        // 3. Delete the office
        $office->delete();

        // Optionally, you can return a response indicating success
        return response()->json(['message' => 'Office archived successfully']);
    }
}
