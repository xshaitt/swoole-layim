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
                url: '{{url('/api/init')}}' //接口地址（返回的数据格式见下文）
            } //获取主面板列表信息，下文会做进一步介绍
        });
    });
</script>
</body>
</html>
