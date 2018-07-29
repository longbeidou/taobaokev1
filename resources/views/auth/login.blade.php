<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>登录 - {{ $title or config('website.name')}}</title>
    <link href="/admin/style/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin/style/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/admin/style/css/animate.min.css" rel="stylesheet">
    <link href="/admin/style/css/style.min.css" rel="stylesheet">
    <link href="/admin/style/css/login.min.css" rel="stylesheet">
    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>

</head>

<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-7">
                <div class="signin-info">
                    <div class="logopanel m-b">
                        <!-- <h1>[ H+ ]</h1> -->
                        <img src="/admin/style/img/logo.png" width="10%">
                    </div>
                    <div class="m-b"></div>
                    <h4>欢迎在 <strong>{{ config('website.name') }}</strong> 选购商品</h4>
                    <ul class="m-b">
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 100%人工审核</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 实时维护排查</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 全天持续上新</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 特惠商品重点推荐</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> QQ购物群交流分享</li>
                    </ul>
                    <strong>还没有账号？ <a href="{{ route('register') }}">立即注册&raquo;</a></strong>
                </div>
            </div>
            <div class="col-sm-5">
                <form method="POST" action="{{ route('loginaction') }}">
                    {{ csrf_field() }}
                    
                    @if (count($errors) > 0)
                        <ul class="text-left list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li class="text-left">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <h4 class="no-margins">登录：</h4>
                    <p class="m-t-md">欢迎访问{{ config('website.name') }}！</p>
                    <input type="email" name="email" class="form-control uname" placeholder="邮箱" value="{{ old('email') }}">
                    <input type="password" name="password" class="form-control pword m-b" placeholder="密码"  id="password">
                    <label><input type="checkbox" name="remember"> 记住我</label>
                    <a href="{{ route('pwdemail') }}" style="float:right;">忘记密码了？</a>
                    <button class="btn btn-success btn-block">登录</button>
                </form>
            </div>
        </div>
        <div class="signup-footer">
            <div class="pull-left">
                &copy; <?php echo date('Y'); ?> All Rights Reserved. {{ config('website.domain') }}
            </div>
        </div>
    </div>
</body>

</html>