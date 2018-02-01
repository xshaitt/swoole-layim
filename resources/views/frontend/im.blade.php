<?php
/**
 * Created by PhpStorm.
 * User: xshaitt
 * Date: 2018/1/31
 * Time: 上午11:40
 */
?>
        <!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>LayIM测试</title>
    <link rel="stylesheet" href="{{asset('/layui/css/layui.css')}}" media="all">
</head>
<h1>IM</h1>
<body>
<script src="{{asset('/layui/layui.js')}}"></script>
<script>
    layui.use('layim', function (layim) {
        //基础配置
        layim.config({
            init: {
                url: '{{url('/api/init')}}'
            },
            title: 'IM',
            find: '{{url("/api/find")}}',
            isgroup: false
        });

        var ws = new WebSocket("{{env('SWOOLE_URL_PORT')}}/?token=xshaitt&phone=" + localStorage.imUserPhone);

        ws.onopen = function () {
            // Web Socket 已连接上，使用 send() 方法发送数据
            // console.log($('.layui-table'));
        };

        ws.onmessage = function (evt) {
            var data = eval('(' + evt.data + ')');
            if (data.type === 'add') {
                layim.add({
                    type: 'friend' //friend：申请加好友、group：申请加群
                    , username: data.data.username //好友昵称，若申请加群，参数为：groupname
                    , avatar: data.data.avatar //头像
                    , submit: function (group, remark, index) {
                        ws.send('{"type":"confirm","target_phone":"' + data.data.id + '","group":"' + group + '","remark":"' + remark + '"}');
                        layer.close(index);
                    }
                });
            } else if (data.type === 'confirm') {
                console.log(data);
                layim.setFriendGroup({
                    type: 'friend'
                    , username: data.source_user.username //好友昵称，若申请加群，参数为：groupname
                    , avatar: data.source_user.avatar //头像
                    , group: layim.cache().friend //获取好友列表数据
                    , submit: function (group, index) {
                        //发送同意通知
                        ws.send('{"type":"ok","source_phone":"' + data.source_phone + '","target_phone":"' + localStorage.imUserPhone + '","group":"' + group + '"}');
                        layer.close(index);
                    }
                });
            } else if (data.type === 'ok') {
                layim.addList(data.user);
            }
        };

        ws.onclose = function () {
            // 关闭 websocket
            console.log('链接关闭')
        };

        layim.on('sendMessage', function (res) {
            var mine = res.mine;
            var to = res.to;
            ws.send(JSON.stringify({
                type: 'message' //随便定义，用于在服务端区分消息类型
                , data: res
            }));
        });
    });
</script>
</body>
</html>
