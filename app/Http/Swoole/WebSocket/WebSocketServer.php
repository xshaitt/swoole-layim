<?php
/**
 * User: xshaitt
 * Date: 2018/1/22
 * Time: 下午5:01
 *                             _ooOoo_
 *                            o8888888o
 *                            88" . "88
 *                            (| -_- |)
 *                            O\  =  /O
 *                         ____/`---'\____
 *                       .'  \\|     |//  `.
 *                      /  \\|||  :  |||//  \
 *                     /  _||||| -:- |||||-  \
 *                     |   | \\\  -  /// |   |
 *                     | \_|  ''\---/''  |   |
 *                     \  .-\__  `-`  ___/-. /
 *                   ___`. .'  /--.--\  `. . __
 *                ."" '<  `.___\_<|>_/___.'  >'"".
 *               | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *               \  \ `-.   \_ __\ /__ _/   .-` /  /
 *          ======`-.____`-.___\_____/___.-`____.-'======
 *                             `=---='
 *          ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 *                     佛祖保佑        永无BUG
 *
 *                      江城子 . 程序员之歌
 *
 *                  十年生死两茫茫，写程序，到天亮。
 *                      千行代码，Bug何处藏。
 *                  纵使上线又怎样，朝令改，夕断肠。
 *
 *                  领导每天新想法，天天改，日日忙。
 *                      相顾无言，惟有泪千行。
 *                  每晚灯火阑珊处，夜难寐，加班狂。
 */

namespace App\Http\Swoole\WebSocket;

use Illuminate\Support\Facades\Redis;

class WebSocketServer
{
    public $server;
    public $fds = [];
    public $userIds = [];

    public function start()
    {
        $this->server = new \swoole_websocket_server("0.0.0.0", 9501, SWOOLE_BASE);
        $this->server->on('open', $this->open());
        $this->server->on('message', $this->message());
        $this->server->on('close', $this->close());
        $this->server->start();
    }

    public function open()
    {
        return function (\swoole_websocket_server $server, $request) {
            //token不合法则断开链接
            if (empty($request->get['token']) || $request->get['token'] != 'xshaitt') {
                $server->close($request->fd);
            }
            $this->fds[$request->fd] = $request->get['phone'];
            $this->userIds[$request->get['phone']][$request->fd] = $request->fd;
            var_dump($this->userIds);
        };
    }

    public function message()
    {
        return function (\swoole_websocket_server $server, $frame) {
            $data = json_decode($frame->data, true);
            if ($data['type'] == 'find') {
                $keys = Redis::keys("im:user:*{$data['phone']}*:mine");
                $result = [];
                foreach ($keys as $key) {
                    $result[] = Redis::hgetall($key);
                }
                $response['type'] = 'find';
                $response['data'] = $result;
                $server->push($frame->fd, json_encode($response));
            } elseif ($data['type'] == 'add') {
                $response['type'] = 'add';
                $result = Redis::hgetall("im:user:{$data['other_phone']}:mine");
                $response['data'] = $result;
                foreach ($this->userIds[$data['my_phone']] as $key => $val) {
                    $server->push($key, json_encode($response));
                }
            } elseif ($data['type'] == 'confirm') {
                $response['type'] = 'confirm';
                $targetPhone = $data['target_phone'];
                $sourcePhone = $this->fds[$frame->fd];
                $remark = $data['remark'];
                $response['target_phone'] = $targetPhone;
                $response['source_phone'] = $sourcePhone;
                $response['remark'] = $remark;
                $response['source_user'] = Redis::hgetall("im:user:{$sourcePhone}:mine");
                foreach ($this->userIds[$targetPhone] as $key => $val) {
                    $server->push($key, json_encode($response));
                }
            } elseif ($data['type'] == 'ok') {
                //建立好友关系
                $targetPhone = $data['target_phone'];
                $sourcePhone = $data['source_phone'];
                $targetFriend = json_decode(Redis::get("im:user:{$targetPhone}:friend"), true);
                $sourceFriend = json_decode(Redis::get("im:user:{$sourcePhone}:friend"), true);
                $targetUser = Redis::hgetall("im:user:{$targetPhone}:mine");
                $sourceUser = Redis::hgetall("im:user:{$sourcePhone}:mine");
                $targetFriend[0]['list'][] = $sourceUser;
                $sourceFriend[0]['list'][] = $targetUser;
                Redis::set("im:user:{$targetPhone}:friend", json_encode($targetFriend));
                Redis::set("im:user:{$sourcePhone}:friend", json_encode($sourceFriend));
                //发送添加好友到面板通知
                $response['type'] = 'ok';
                $targetUser['type'] = 'friend';
                $targetUser['groupid'] = 0;
                $sourceUser['type'] = 'friend';
                $sourceUser['groupid'] = 0;
                $response['user'] = $sourceUser;
                foreach ($this->userIds[$targetPhone] as $key => $val) {
                    $server->push($key, json_encode($response));
                }
                $response['user'] = $targetUser;
                foreach ($this->userIds[$sourcePhone] as $key => $val) {
                    $server->push($key, json_encode($response));
                }
            } elseif ($data['type'] == 'message') {
                //监听到消息
                $targetPhone = $data['data']['to']['id'];
                $response['type'] = 'message';
                $response['data']['username'] = $data['data']['mine']['username'];
                $response['data']['avatar'] = $data['data']['mine']['avatar'];
                $response['data']['id'] = $data['data']['mine']['id'];
                $response['data']['type'] = $data['data']['to']['type'];
                $response['data']['content'] = $data['data']['mine']['content'];
//                $response['data']['username'] = $data['data']['mine']['username'];
                $response['data']['mine'] = false;
                $response['data']['fromid'] = $data['data']['mine']['id'];
                $response['data']['timestamp'] = time() * 1000;
                foreach ($this->userIds[$targetPhone] as $key => $val) {
                    $server->push($key, json_encode($response));
                }
            }
        };
    }

    public function close()
    {
        return function ($ser, $fd) {
            //注销fds属性里面对应的数据
            $userId = @$this->fds[$fd];
            unset($this->userIds[$userId][$fd]);
            unset($this->fds[$fd]);
            if (count($this->userIds[$userId]) < 1) {
                unset($this->userIds[$userId]);
            }
            var_dump($this->userIds);
        };
    }
}