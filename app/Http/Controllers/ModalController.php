<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function triggerModal($params){
        // dd($params);
        return view('departments.components.contents.home')->with(['modal'=>$params]);
    }
}
