<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Swoole\Websocket\WebSocketClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function test()
    {
        return view('frontend/welcome');
    }

    public function register()
    {
        return view('frontend/register');
    }

    public function createUser(Request $request)
    {
        $phone = $request->input('phone', '');
        $username = $request->input('username', '');
        if (!Redis::exists("im:user:{$phone}:mine")) {
            Redis::hset("im:user:{$phone}:mine", 'id', $phone, 'username', $username, 'sign', '在深邃的编码世界，做一枚轻盈的纸飞机',
                'status', 'online', 'avatar', '//res.layui.com/images/fly/avatar/00.jpg');
            $friendData = [['groupname' => '我的好友', 'id' => 0, 'list' => []]];
            Redis::set("im:user:{$phone}:friend", json_encode($friendData));
        }
        //跳转到聊天页面
        dd('xxx');
        return redirect(url('/api/im'));
    }

    public function im()
    {
        return view('frontend/im');
    }

    public function init(Request $request)
    {
        $phone = $request->input('phone', '17091642345');
        $mine = Redis::hgetall("im:user:{$phone}:mine");
        $friend = json_decode(Redis::get("im:user:{$phone}:friend"));
        $response['code'] = 0;
        $response['msg'] = "";
        $response['data']['mine'] = $mine;
        $response['data']['friend'] = $friend;
        return $response;
    }
}
