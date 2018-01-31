<?php
/**
 * Created by PhpStorm.
 * User: xshaitt
 * Date: 2018/1/31
 * Time: 下午1:19
 */
?>
        <!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>LayIM测试</title>
    <link rel="stylesheet" href="{{asset('/layui/css/layui.css')}}" media="all">
</head>
<h1>创建用户开始聊天吧</h1>
<body>
<form class="layui-form" method="post" onsubmit="return saveUser()"> <!-- 提示：如果你不想用form，你可以换成div等任何一个普通元素 -->
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
            <input type="text" name="phone" placeholder="手机号" autocomplete="off" class="layui-input input-phone"
                   lay-verify="required|phone|number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">昵称</label>
        <div class="layui-input-block">
            <input type="text" name="nickname" placeholder="昵称" autocomplete="off" class="layui-input input-nickname"
                   lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
    <!-- 更多表单结构排版请移步文档左侧【页面元素-表单】一项阅览 -->
</form>
<script src="{{asset('/layui/layui.js')}}"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    //如果本地已经登录则直接登录到聊天页
    if (localStorage.imUserPhone !== undefined) {
        location.href = "{{url('/api/im')}}";
    }
    layui.use('form', function () {
        var form = layui.form;

        //各种基于事件的操作，下面会有进一步介绍
    });

    function saveUser() {
        localStorage.imUserPhone = $('.input-phone').val();
        localStorage.imUserNickname = $('.input-nickname').val();
        return true;
    }
</script>
</body>
</html>

