<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\RequestedDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\RequestedDocumentController;

class ReportController extends Controller
{
    public function getOnGoingDocuments()
    {
        $documents = RequestedDocument::where(function ($query) {
            $query->where('requestor_user', Auth::user()->id)
                ->orWhere('forwarded_to', Auth::user()->id);
        })
        ->get();

        // Subquery to get the maximum log created_at timestamp for each requested document
        $subquery = DB::table('logs')
            ->select('requested_document_id', DB::raw('MAX(created_at) as max_created_at'))
            ->whereIn('requested_document_id', $documents->pluck('id'))
            // ->where('destination', Auth::user()->id) // Additional condition for destination
            ->groupBy('requested_document_id');
        
        // Get the latest logs for each requested document
        $latestLogs = DB::table('logs')
            ->joinSub($subquery, 'latest_logs', function ($join) {
                $join->on('logs.requested_document_id', '=', 'latest_logs.requested_document_id')
                    ->on('logs.created_at', '=', 'latest_logs.max_created_at');
            })
            ->get();
        
        // Group documents by status and get the count for each group
        $statusCounts = $documents->groupBy('status')->map(function ($group) {
            return $group->count();
        });
        
        // Group logs by status and get the count for each group
        $logStatusCounts = $latestLogs->groupBy('status')->map(function ($group) {
            return $group->count();
        });
        
        // Create an associative array for the response
        $response = [
            'forwarded' => $statusCounts->get('forwarded', 0),
            'accomplished' => $statusCounts->get('accomplished', 0),
            'rejected' => $statusCounts->get('rejected', 0),
            'pending' => $statusCounts->get('pending', 0),
        ];
        
        // Add log counts to the response
        $response['assigned'] = $logStatusCounts->toArray();
// dd($response);
return response()->json(['response' => $response]);
      
    }
}
