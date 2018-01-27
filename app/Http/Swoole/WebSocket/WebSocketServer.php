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
//        $this->server->on('handshake', $this->handshake());
        $this->server->on('open', $this->open());
        $this->server->on('message',$this->message() );
        $this->server->on('close',$this->close() );
//        $this->server->on('request',$this->request() );
        $this->server->start();
    }

    public function open()
    {
        return function (\swoole_websocket_server $server, $request) {
            //token不合法则断开链接
            if(empty($request->get['token']) || $request->get['token'] != 'xshaitt'){
                return false;
            }
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

    public function handshake()
    {
        return function (\swoole_http_request $request, \swoole_http_response $response){
            //自定定握手规则，没有设置则用系统内置的（只支持version:13的）
            if (!isset($request->header['sec-websocket-key']))
            {
                //'Bad protocol implementation: it is not RFC6455.'
                $response->end();
                return false;
            }
            if (0 === preg_match('#^[+/0-9A-Za-z]{21}[AQgw]==$#', $request->header['sec-websocket-key'])
                || 16 !== strlen(base64_decode($request->header['sec-websocket-key']))
            )
            {
                //Header Sec-WebSocket-Key is illegal;
                $response->end();
                return false;
            }

            $key = base64_encode(sha1($request->header['sec-websocket-key']
                . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11',
                true));
            $headers = array(
                'Upgrade'               => 'websocket',
                'Connection'            => 'Upgrade',
                'Sec-WebSocket-Accept'  => $key,
                'Sec-WebSocket-Version' => '13',
                'KeepAlive'             => 'off',
            );
            foreach ($headers as $key => $val)
            {
                $response->header($key, $val);
            }
            $response->status(101);
            $response->end();
            return true;
        };
    }

    public function request()
    {
        return function (\swoole_http_request $request, \swoole_http_response $response){
            $response->end('111');
        };
    }
}