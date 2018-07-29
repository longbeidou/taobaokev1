<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    @section('seohead')
    @show

    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/admin/style/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/admin/style/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/admin/style/css/animate.min.css" rel="stylesheet">
    <link href="/admin/style/css/style.min.css?v=4.0.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="wrapper">
        <!--左侧导航开始-->
        @include('admin.layouts.DashboardLeftNav')
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            @include('admin.layouts.DashboardTop')
            @include('admin.layouts.DashboardTab')
            @include('admin.layouts.DashboardContent')
            @include('admin.layouts.DashboardFooter')
        </div>
        <!--右侧部分结束-->
        <!--右侧边栏开始-->
            @include('admin.layouts.DashboardRightNav')
            
        <!--右侧边栏结束-->
        <!--mini聊天窗口开始-->            
            {{--@include('admin.layouts.DashboardMiniTalk')--}}
    </div>
    <script src="/admin/style/js/jquery.min.js?v=2.1.4"></script>
    <script src="/admin/style/js/bootstrap.min.js?v=3.3.5"></script>
    <script src="/admin/style/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/admin/style/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/admin/style/js/plugins/layer/layer.min.js"></script>
    <script src="/admin/style/js/hplus.min.js?v=4.0.0"></script>
    <script type="text/javascript" src="/admin/style/js/contabs.min.js"></script>
    <script src="/admin/style/js/plugins/pace/pace.min.js"></script>
</body>

</html>