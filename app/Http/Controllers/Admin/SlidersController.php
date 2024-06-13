<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SlidersController extends Controller
{
    public function create()
    {
        return view('admin.slider.create_edit');
    }
}