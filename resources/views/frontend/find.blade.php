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
        *{box-sizing: border-box;-webkit-box-sizing: border-box;margin: 0;padding:0;}
        .container{width: 400px;height: 400px;padding-top: 100px;vertical-align: middle;position: relative;}
        .container input{margin-left:80px;width: 200px;height: 35px;border:1px solid #ccc;outline: none;}
        .container button{width: 40px;height: 35px;border:none;outline: none;background:#1890ff;color:#fff;}
        .container ul{position: absolute;width: 200px;left:80px;top:135px;}
        .container ul li{list-style: none;height:50px;display: flex;flex:1;align-items:center;justify-content: center;border: 1px solid #ccc;border-top:none;}
        /*ul li{width: 60px;};*/
        ul li img{width: 30px;height: 30px;background: blue;border-radius: 50%;vertical-align: middle;}
    </style>
</head>
<body>
<div class='container'><input type='text'/><button>搜索</button>
    <ul>
        <li>
            <div>
                <img src="http://kibey-sys-avatar.b0.upaiyun.com/01ba2ac493978fea02ab4ecbbee1b578.jpg" alt="">
            </div>
            <div>saasssdcds</div>
        </li>
        <li>
            <div>
                <img src="" alt="">
            </div>
            <div>saasssdcds</div>
        </li>
    </ul>
</div>
</body>
</html>
