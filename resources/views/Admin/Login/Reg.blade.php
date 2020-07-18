<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>后台登录</title>
    <meta name="author" content="DeathGhost" />
    <link rel="stylesheet" type="text/css" href="/static/Admin/Login/Css/style.css" />
    <style>
        body{height:100%;background:#16a085;overflow:hidden;}
        canvas{z-index:-10;position:absolute;}
    </style>
    <script src="/static/Admin/Login/Scripts/jquery.js"></script>
    <script src="/static/Admin/Login/Scripts/verificationnumbers.js"></script>
    <script src="/static/Admin/Login/Scripts/particleground.js"></script>
</head>
<body>
<dl class="admin_login">
    <dt>
        <strong>注册后台管理系统</strong>
        <em>Yang wen long's background login</em>
    </dt>
    <center>
        <font color="red">{{session("get")}}</font>
    </center>
    <form action="{{url('/user/registered')}}" method="post">
        @csrf
        <dd class="user_icon">
            <input type="text"  name="user_name" placeholder="用户名" class="login_txtbx"/>
        </dd>
        <dd class="user_icon">
            <input type="text"  name="email" placeholder="Email" class="login_txtbx"/>
        </dd>
        <dd class="pwd_icon">
            <input type="password" name="password" placeholder="密码" class="login_txtbx"/>
        </dd>
        <dd class="pwd_icon">
            <input type="password" name="pwd" placeholder="确认密码" class="login_txtbx"/>
        </dd>
        <dd>
            <input type="submit" value="立即注册" class="submit_btn"/>
        </dd>
    </form>
    <dd>
        <p>© 2015-2016 DeathGhost 版权所有</p>
        <p>文 - 2382662404@qq.com -</p>
    </dd>
</dl>
</body>
</html>
<script>
    $(document).ready(function() {
        //粒子背景特效
        $('body').particleground({
            // dotColor: '#5cbdfa',
            dotColor: '#afccde',
            lineColor: '#5cbdfa'
        });
        //验证码
        createCode();
    });
</script>