<?php

namespace App\Http\Frontend\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        return view('frontend/welcome');
    }
}
