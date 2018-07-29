<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" src="/admin/style/img/profile_small.jpg" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                       <span class="block m-t-xs"><strong class="font-bold">{{$user->name}}</strong></span>
                        <span class="text-muted text-xs block">超级管理员<b class="caret"></b></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <!-- <li><a class="J_menuItem" href="/">修改头像</a></li> -->
                        <li><a class="J_menuItem" href="{{route('changepwd')}}">修改密码</a></li>
                        <!-- <li><a class="J_menuItem" href="/">个人资料</a></li> -->
                        <li class="divider"></li>
                        <li><a href="{{ route('logout') }}">安全退出</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img src="/admin/style/img/logo.png" width="50%">
                </div>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">商品分析</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('couponAnalysisIndex') }}" data-index="0">优惠券商品</a>
                    </li>
                    <!-- <li>
                        <a class="J_menuItem" href="index_v2.html">选品库商品</a>
                    </li> -->
                </ul>
            </li>

<!--             <li>
                <a href="#">
                    <i class="fa fa-search"></i>
                    <span class="nav-label">热门搜索</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="index_v1.html" data-index="0">首页搜索</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="index_v2.html">优惠券商品</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="index_v3.html">九块九包邮</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="index_v4.html">二十元封顶</a>
                    </li>
                    <li>
                        <a href="index_v5.html" target="_blank">鼎力推荐</a>
                    </li>
                    <li>
                        <a href="index_v5.html" target="_blank">热门搜索分类</a>
                    </li>
                </ul>
            </li> -->

            <li>
                <a href="#">
                    <i class="fa fa-certificate"></i>
                    <span class="nav-label">栏目管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{route('adminCategoryIndex')}}" data-index="0">栏目列表</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{route('adminCategoryAdd')}}">添加栏目</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-gift"></i>
                    <span class="nav-label">优惠券商品</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('couponlist') }}" data-index="0">优惠券商品列表</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{ route('couponCategoryList') }}">优惠券分类列表</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{ route('couponCategoryAdd') }}">增加优惠券分类</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-star-half-o"></i>
                    <span class="nav-label">选品库商品</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{route('favoritesDashboardList')}}">设置选品库列表分类</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{ route('favoritesList') }}">栏目对应的选品库列表</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{ route('favoritesItemGoodsList') }}">栏目对应的选品库商品</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="nav-label">用户管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{route('changepwd')}}">修改密码</a>
                    </li>
                </ul>
            </li> 

            <li>
                <a href="#">
                    <i class="fa fa-cloud"></i>
                    <span class="nav-label">更新数据库</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a class="J_menuItem" href="{{ route('listexcelupload') }}">优惠券数据库</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{route('favoritesDashboard')}}">选品库列表数据库</a>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{{route('favoritesItemDashboard')}}">选品库宝贝数据库</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>