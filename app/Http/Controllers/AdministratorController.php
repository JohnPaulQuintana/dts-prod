<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Events\NotifyEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    public function dashboard(){
        return view('admin.components.contents.home')->with(['logs'=>$this->LogsInfo()]);
    }

    // logs return
    public function LogsInfo(){
        // Retrieve the logged-in user's ID
        $userId = Auth::user()->id;


        // Retrieve logs for the logged-in user
        $logs = Log::where('requested_document_id', $userId)->get();

        // Retrieve the user's department based on their ID
        // $userDepartment = User::where('id', $logs['requested_to'])->value('department');

        // Format the created_at timestamps as "year-month-day"
        $logs = $logs->map(function ($log) {
            $log->formatted_created_at = $log->created_at->format('Y-m-d');
            $log->formatted_time = date('h:i A', strtotime($log->created_at)); // Format the timestamp
            return $log;
        });

        // Add the user's department to each log entry im using roles 
        $logs = $logs->map(function ($log){
            $log->user_department = User::where('id', $log->requested_by)->value('department');;
            return $log;
        });

        return $logs;
    }

    // departments return
    public function availableDepartments(){
        $firstDepartment = User::where('role', 1)
                            ->orderBy('id', 'asc') // You can adjust the ordering as needed
                            ->first();

        $departments = User::where('department', '<>', $firstDepartment->department)
                        ->pluck('department')
                        ->toArray();

        // Insert the first department at the beginning of the array
        array_unshift($departments, $firstDepartment->department);

        return $departments;
    }

    // manage Account
    public function accounts(Request $request){
        // dd($request);
        $id = $request->input('id');
        $req = $request->input('req');
        switch ($req) {
            case 'archived':
                // Find the user by ID
                $user = User::find($id);
                // Mark the user as unverified (set email_verified_at to null)
                // $user->email_verified_at = null;
                $user->status = 'archived';
                $user->password = Hash::make('archived');
                $status = 'success';
                $message = 'Account is successfully set to archived!';
                break;
            case 'activate':
                // Find the user by ID
                $user = User::find($id);
                // Mark the user as unverified (set email_verified_at to null)
                $user->email_verified_at = null;
                $user->status = 'deactivated';
                $user->password = Hash::make('password');
                $status = 'success';
                $message = 'Account is successfully activated, user can activate its email to login!';
                break;
            case 'forgot-password':
                // Find the user by ID
                $user = User::find($id);
                // Mark the user as unverified (set email_verified_at to null)
                $user->email_verified_at = null;
                $user->status = 'deactivated';
                $user->password = Hash::make('password');//default its password
                $status = 'success';
                $message = 'Account is successfully reset the password, user can activate its email to login!';
                break;
            
            default:
                # code...
                break;
        }
        $user->save();
        event(new NotifyEvent('acounts archived'));
        return response()->json(['status'=>$status,'message'=>$message],200);
    }

    //history logs
    public function history(){
        $logs = DB::table('logs')
        ->join('requested_documents', 'logs.requested_document_id', '=', 'requested_documents.id')
        ->join('users', 'requested_documents.requestor_user', '=' ,'users.id')
        ->select('logs.*', 
            'requested_documents.requestor_user', 'requested_documents.status', 'requested_documents.purpose',
            'users.name'
            )
        ->get();

        // dd($logs);
        return view('admin.components.contents.history')->with(['history'=>$logs]);
    }
}
