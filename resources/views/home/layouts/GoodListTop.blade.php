<div class="container-fluid topcontainer hidden-xs">
  <div class="container">
    <!-- top部分 -->
    <div class="row">
      <div class="col-md-3 col-sm-4">
        <img src="{{config('website.logo')}}">
      </div>

      <div class="col-md-6 col-sm-8">
        <div class="row text-center">
          <div class="col-md-1"></div>
          <div class="col-md-10">
            <form class="form-horizontal" action="/chaxuninfo" method="get" target="_blank">
              <div class="form-group">
                <div class="col-sm-10 left">
                  <input type="text" name="q" class="form-control form-control-add borderRadius0" placeholder="请输入要搜索的商品">
                </div>
                <div class="col-sm-2 right">
                  <button type="submit" class="btn btn-red borderRadius0">搜索商品</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="row text-center">
          <ul class="list-inline">
            <li><strong>热门搜索:</strong></li>
            <li><a href="/chaxuninfo?q=男装" target="_blank" class="ared">男装</a></li>
            <li><a href="/chaxuninfo?q=女装" target="_blank" class="ared">女装</a></li>
            <li><a href="/chaxuninfo?q=零食" target="_blank" class="ared">零食</a></li>
            <li><a href="/chaxuninfo?q=丝袜" target="_blank" class="ared">丝袜</a></li>
            <li><a href="/chaxuninfo?q=男鞋" target="_blank" class="ared">男鞋</a></li>
            <li><a href="/chaxuninfo?q=女鞋" target="_blank" class="ared">女鞋</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-3 text-center hidden-sm">
        <div class="row">
          <div class="col-md-4 text-center fonttopimg"><img src="/home/style/img/top01.png"></div>
          <div class="col-md-4 text-center fonttopimg"><img src="/home/style/img/top02.png"></div>
          <div class="col-md-4 text-center fonttopimg"><img src="/home/style/img/top03.png"></div>
        </div>
        <div class="row">
          <div class="col-md-4 text-center fonttop">100%人工审核</div>
          <div class="col-md-4 text-center fonttop">实时维护排查</div>
          <div class="col-md-4 text-center fonttop">全天持续上新</div>
        </div>
      </div>
    </div>
  </div>
</div>
