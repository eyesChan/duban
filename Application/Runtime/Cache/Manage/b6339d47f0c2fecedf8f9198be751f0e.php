<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="/Public/css/base.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/login.css" />
        <script type="text/javascript" src="/Public/Js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="/Public/Js/placeholder.js"></script>
        <script type="text/javascript" src="/Public/Js/login.js"></script>
    </head>
    <body>
        <!--登录部分start-->
        <div id="login">
              <!-- 登录部分主要内容start-->
                 <div class="login-rt">
                      
                        <!-- 表单部分-->
                        <div class="login-form">
                            <div class="form-title">账号登录</div>
                            <form name="form1" method="post" action="">
                                <ul>
                                    <li class="common-login-list">
                                        <input class="login-common-input login-id" type="text" placeholder="用户名" name="nickname" id="nickname" maxlength="20"/>
                                        <div class="error-message error-id hide">
                                            <img src="/Public/images/login//error.png" alt=""/>
                                            <span>请输入用户名！</span>
                                        </div>
                                    </li>
                                    <li class="common-login-list">
                                        <input class="login-common-input login-pwd"  type="password" placeholder="密码" name="password" id="password" maxlength="30"/>
                                        <div class="error-message error-pwd hide">
                                            <img src="/Public/images/login/error.png" alt=""/>
                                            <span>请输入密码！</span>
                                        </div>
                                    </li>
                                    <li class="common-login-list clearfix login-verification-list">
                                        <input class="fl login-common-input login-verification" type="text" placeholder="验证码" name="" id="verify_code"/>
                                        <img id="checkCode" class="verifyimg reloadverify" alt="点击切换" src="<?php echo U('Login/verify');?>">
                                        <div class="error-message error-ver hide">
                                            <img src="/Public/images/login/error.png" alt=""/>
                                            <span>请输入验证码！</span>
                                        </div>
                                    </li>
                                    <li>
                                        <input class="login-button" type="button" value="登录"/>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
        </div>
        <!--登录部分end-->
    </body>
</html>