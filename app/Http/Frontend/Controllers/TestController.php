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
        $prot = 9500;
        $client = new WebSocketClient($host, $prot);
        $data = $client->connect();
        $sendData['time'] = date('Y-m-d H:i:s', time());
        $sendData['type'] = ['买', '卖'][rand(0, 1)];
        $sendData['price'] = mt_rand(10000, 30000);
        $sendData['number'] = mt_rand(2, 20);
        $sendData['sum'] = $sendData['price'] * $sendData['number'];
        $result = $client->send(json_encode($sendData));
        return json_encode($result);
    }
}
