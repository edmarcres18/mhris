<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{

    //public function index()
    public function index()
    {
        return view('messaging.index');
    }
}
