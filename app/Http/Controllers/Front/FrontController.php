<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        // Your code here
        return view('front.index');
    }
}