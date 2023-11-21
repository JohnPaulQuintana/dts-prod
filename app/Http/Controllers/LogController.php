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
        $alreadyPs = Log::where('destination', Auth::user()->id)
        ->where('scanned', 1)->where('status', 'approved')
        ->where('requested_document_id', $request->input('doc_id'))->exists();
        return $alreadyPs;
    }
}
