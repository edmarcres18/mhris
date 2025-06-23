<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetAppController extends Controller
{
    public function index()
    {
        return view('get-the-app');
    }
}
