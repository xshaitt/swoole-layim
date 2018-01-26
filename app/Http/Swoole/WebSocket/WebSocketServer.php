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

class WebSocketServer
{
    public $server;
    public $fds9500 = [];
    public $fds9501 = [];

    public function start()
    {
        $this->server = new \swoole_websocket_server("0.0.0.0", 9501, SWOOLE_BASE);
        $this->server->addlistener('0.0.0.0', 9500, SWOOLE_SOCK_TCP);
        $this->server->on('open', $this->open());
        $this->server->on('message',$this->message() );
        $this->server->on('close',$this->close() );
        $this->server->start();
    }

    public function open()
    {
        return function (\swoole_websocket_server $server, $request) {
            $serverInfo = $server->connection_info($request->fd);
            if ($serverInfo['server_port'] == 9500) {
                $this->fds9500[$request->fd] = $request->fd;
            }elseif ($serverInfo['server_port'] == 9501) {
                $this->fds9501[$request->fd] = $request->fd;
            }
        };
    }

    public function message()
    {
        return function (\swoole_websocket_server $server, $frame) {
            //如果是从9500商品接收到的消息则推荐给所有的9501端口
            $serverInfo = $server->connection_info($frame->fd);
            if ($serverInfo['server_port'] == 9500) {
                foreach ($this->fds9501 as $key => $value) {
                    $server->push($value, $frame->data);
                }
            }
        };
    }

    public function close()
    {
        return function ($ser, $fd) {
            //注销对应数据里面的数据
            if (in_array($fd, $this->fds9500)) {
                unset($this->fds9500[$fd]);
                echo '删除9500fd' . $fd;
            } elseif (in_array($fd, $this->fds9501)) {
                unset($this->fds9501[$fd]);
                echo '删除9501fd' . $fd;
            }
        };
    }
}