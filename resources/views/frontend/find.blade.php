<?php
/**
 * Created by PhpStorm.
 * User: xshaitt
 * Date: 2018/1/31
 * Time: 下午4:32
 */
?>

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>搜索好友</title>
    <style>
        * {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            height: 400px;
            padding-top: 100px;
            vertical-align: middle;
            position: relative;
        }

        .container input {
            margin-left: 80px;
            width: 200px;
            height: 35px;
            border: 1px solid #ccc;
            outline: none;
        }

        .container button {
            width: 40px;
            height: 35px;
            border: none;
            outline: none;
            background: #1890ff;
            color: #fff;
        }

        .container ul {
            position: absolute;
            width: 200px;
            left: 80px;
            top: 135px;
        }

        .container ul li {
            list-style: none;
            height: 50px;
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: center;
            border: 1px solid #ccc;
            border-top: none;
        }

        /*ul li{width: 60px;};*/
        ul li img {
            width: 30px;
            height: 30px;
            background: blue;
            border-radius: 50%;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class='container'>
    <input type='text' class="phone"/>
    <button class="search">搜索</button>
    <ul class="find-list">
        {{--<li>--}}
        {{--<div>--}}
        {{--<img src="http://kibey-sys-avatar.b0.upaiyun.com/01ba2ac493978fea02ab4ecbbee1b578.jpg" alt="">--}}
        {{--</div>--}}
        {{--<div>saasssdcds</div>--}}
        {{--</li>--}}
        {{--<li>--}}
        {{--<div>--}}
        {{--<img src="" alt="">--}}
        {{--</div>--}}
        {{--<div>saasssdcds</div>--}}
        {{--</li>--}}
    </ul>
</div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    if ("WebSocket" in window) {

        // 打开一个 web socket
        var ws = new WebSocket("{{env('SWOOLE_URL_PORT')}}/?token=xshaitt&phone=" + localStorage.imUserPhone);

        ws.onopen = function () {
            // Web Socket 已连接上，使用 send() 方法发送数据
            // console.log($('.layui-table'));
        };

        ws.onmessage = function (evt) {
            var data = eval('(' + evt.data + ')');
            if (data.type == 'find' && data.data.length > 0) {
                //查找用户
                $('.find-list').html('');
                for (var i = 0; i < data.data.length; i++) {
                    $('.find-list').append('<li>\n' +
                        '            <div>\n' +
                        '                <img src="' + data.data[i].avatar + '" alt="">\n' +
                        '            </div>\n' +
                        '            <div>' + data.data[i].username + '</div><button phone="' + data.data[i].id + '" class="add-user">添加</button>\n' +
                        '        </li>')
                }
            }
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
    //查找用户
    $('.search').click(function () {
        ws.send('{"type":"find","phone":"' + $('.phone').val() + '"}');
    })

    $('.find-list').on('click', '.add-user', function (e) {
        ws.send('{"type":"add","other_phone":"' + $(this).attr('phone') + '","my_phone":' + localStorage.imUserPhone + '}');
    })

</script>
</html>
