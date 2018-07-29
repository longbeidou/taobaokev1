<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" method="get" action="/">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="">
                <a href="/" target="_blank" data-index="0"><i class="glyphicon glyphicon-home"></i> 网站首页</a>
            </li>
     <!--        <li class="dropdown hidden-xs">
                <a class="right-sidebar-toggle" aria-expanded="false">
                    <i class="fa fa-tasks"></i> 主题
                </a>
            </li> -->
        </ul>
    </nav>
</div>