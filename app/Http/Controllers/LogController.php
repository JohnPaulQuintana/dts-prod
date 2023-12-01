<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function alreadyProcessed(Request $request)
    {
        // dd($request);
        $alreadyPs = Log::select('logs.*', 'requested_documents.po', 'requested_documents.pr')
            ->join('requested_documents', 'logs.requested_document_id', '=', 'requested_documents.id')
            ->where('destination', Auth::user()->id)
            ->where('requested_document_id', $request->input('doc_id'))
            ->latest('created_at')
            ->first();
        return $alreadyPs;
    }
}
