<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['request-text'=>'required']);

        $document = new Request([]);

        return response()->json(['message' => 'File upload failed'], 400);
    }
}
