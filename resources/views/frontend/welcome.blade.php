<?php
/**
 * User: xshaitt
 * Date: 2018/1/22
 * Time: 下午3:26
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
?>

        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WebSocket测试</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{asset('/layui/css/layui.css')}}" media="all">
    <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
<h1>最近交易记录</h1>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>时间</th>
        <th>买/卖</th>
        <th>价格</th>
        <th>数量</th>
        <th>合计</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>2018-1-23 11:22:22</td>
        <td>买</td>
        <td>1600</td>
        <td>2</td>
        <td>3200</td>
    </tr>
    <tr>
        <td>2018-1-23 10:22:22</td>
        <td>卖</td>
        <td>1600</td>
        <td>2</td>
        <td>3200</td>
    </tr>
    </tbody>
</table>


<script src="{{asset('/layui/layui.js')}}" charset="utf-8"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script type="text/javascript">
    function WebSocketTest() {
        if ("WebSocket" in window) {

            // 打开一个 web socket
            var ws = new WebSocket("ws://0.0.0.0:9501/?token=xshaitt");

            ws.onopen = function () {
                // Web Socket 已连接上，使用 send() 方法发送数据
                ws.send("发送数据");
                // console.log($('.layui-table'));
            };

            ws.onmessage = function (evt) {
                $data = eval('(' + evt.data + ')');
                //更新表格
                $('.layui-table').append(
                    '<tr><td>' + $data.time + '</td><td>' + $data.type + '</td><td>' + $data.price + '</td><td>' + $data.number + '</td><td>' + $data.sum + '</td></tr>'
                )
            };

            ws.onclose = function () {
                // 关闭 websocket
                console.log('链接关闭')
            };
        }

        else {
            // 浏览器不支持 WebSocket
            alert("您的浏览器不支持 WebSocket!");
        }
    }

    WebSocketTest();
</script>
</body>
</html>