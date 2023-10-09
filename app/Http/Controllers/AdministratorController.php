<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
