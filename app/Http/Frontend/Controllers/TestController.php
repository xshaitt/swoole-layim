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

    public function send()
    {
        $host = '0.0.0.0';
        $prot = 9501;
        $client = new WebSocketClient($host, $prot);
        $data = $client->connect();
        $client->send("这是一条客户端发送的消息");
    }
}
