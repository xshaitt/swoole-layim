<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Swoole\Websocket\WebSocketClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function test()
    {
        return view('frontend/welcome');
    }
}
