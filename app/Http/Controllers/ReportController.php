<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\RequestedDocumentController;

class ReportController extends Controller
{
    public function getOnGoingDocuments(){
        $documents = RequestedDocument::all();
        // Group documents by status and get the count for each group
        $statusCounts = $documents->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        // assigned documents
        $assignedDoc = Log::where('forwarded_to', Auth::user()->id)->get();
        
        // Create an associative array for the response
        $response = [
            'forwarded' => $statusCounts->get('forwarded', 0),
            'accomplished' => $statusCounts->get('accomplished', 0),
            'rejected' => $statusCounts->get('rejected', 0),
            'pending' => $statusCounts->get('pending', 0),
        ];

        return response()->json(['response' => $response,'assigned'=>$assignedDoc]);

    }
}
