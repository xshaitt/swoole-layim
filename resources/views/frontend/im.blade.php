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
    });
</script>
</body>
</html>
