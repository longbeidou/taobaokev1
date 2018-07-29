<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Coupon;
use App\Model\CouponCategory;
use App\Model\Category;
use App\Model\FavoritesCategory;
use App\Model\FavoritesItem;

use App\Libraries\Taobaoke\Api;
use App\Libraries\Taobaoke\top\request\WirelessShareTpwdCreateRequest;
use App\Libraries\Taobaoke\top\domain\GenPwdIsvParamDto;

use App\Helpers\functions;

class UrlConfirmController extends Controller
{
    /**
     * 引入淘宝的api
     *
     */
    public $c;

    public function __construct(){
        $this->c = Api::api();
    }

    /**
     * 优惠券详情的URL跳转
     *
     */
    public function couponIndex(Request $request)
    {
        $id = $request->id;

        // id不存在，直接跳转到首页
        if (empty($id)) {
            return redirect('/');
        }

        // 不存在对应id的商品，直接跳转到首页
        $c = new Coupon;
        $n = $c->where('id', $id)->count();
        if (!$n) {
            return redirect('/');
        }

        $c = new Coupon;
        $tbkUrl = $c->where('id', $id)
                    ->first()
                    ->yhq_goods;
        $tbkUrl = trim($tbkUrl); // 淘宝客优惠券的链接

        // 判断是否是来自微信客户端的请求
        $isWechat = $this->isWechat($id);
        if ($isWechat) {
            //来自微信的访问
            $info = $this->couponFromWechat($id);

            return view('home.infomation.CouponInfo', ['info'=>$info,
                                                       'recommendInfo'=>$info->recommend]);
        }

        // 判断是否是来自移动端的请求
        $isMobile = $this->isMobile();
        if ($isMobile) {
            // 来自移动端的请求
            return redirect($tbkUrl); // 跳转到淘宝客优惠券的链接
        } else {
            // 来自PC端的请求
            return redirect($tbkUrl); // 跳转到淘宝客优惠券的链接
        }
    }

    /**
     * 选品库商品详情的URL跳转
     *
     */
    public function favoritesItemIndex($id){
        // id不存在，直接跳转到首页
        if (empty($id)) {
            return redirect('/');
        }

        // 根据id获取选品库商品信息
        $favoritesItem = FavoritesItem::where('id', $id)
                                      ->first();

        // 不存在对应id的商品，直接跳转到首页
        if (!count($favoritesItem)) {
            return redirect('/');
        }

        // 判断是否是来自微信客户端的请求
        $isWechat = $this->isWechat($id);
        if ($isWechat) { // 来自微信端的访问
            // 获取随机推荐的商品
            $recommendInfo = $this->getFavortiesItemRandom(4);

            //　处理淘口令的逻辑
            $favoritesItem->tao_kou_ling = $this->getTaoKouLingFromFavoritesItem($favoritesItem);

            return view('home.infomation.FavoritesItem', ['favoritesItem' => $favoritesItem,
                                                          'recommendInfo' => $recommendInfo
                                                         ]);
        }

        // 判断是否是来自移动端的请求
        $isMobile = $this->isMobile();                                      
        if ($isMobile) {
            // 来自移动端的访问
            return redirect($favoritesItem->click_url);
        } {
            // 来自PC端的访问
            return redirect($favoritesItem->click_url);
        }
    }

    /**
     * 来自微信端的请求对应的返回信息
     *
     */
    public function couponFromWechat($id)
    {
        // 根据对应id获取相应商品的信息
        $c = new Coupon;
        $info = $c->where('id', $id)->first();
        $info->date = explode('-', $info->yhq_end);

        // 判断数据库是否存在淘口令，如果不存在写入数据库
        if (empty($info->tao_kou_ling)) {
            $tkl = ['url'=>$info->yhq_goods, 
                    'text'=>"超值活动，惊喜多多，[".config('website.name')."]亲情推荐！"
                    ];
            $info->tao_kou_ling = getTaoKouLing($tkl);
            $taoKouLingStr = (string)$info->tao_kou_ling;

            // 如果成功获取淘口令写入数据库
            if (strlen($taoKouLingStr) > 4) {
                $m = new Coupon;
                $n = $m->where('id', $id)->update(['tao_kou_ling'=>$info->tao_kou_ling]);
                unset($m);
            } else {
                $info->tao_kou_ling = '';
            }

        }

        // 随机获取4条推荐商品的数据
        $c = new Coupon;
        $recommendInfo = $c->where('is_recommend', '是')
                           ->orderByRaw('RAND()')
                           ->take(4)
                           ->get();

        $info->recommend = $recommendInfo;

        return $info;
    }

    /**
     * 随机获取特定数量的选品库商品
     *
     */
    public function getFavortiesItemRandom($number=4) {
        // 获取特定数量的推荐商品
        $info = FavoritesItem::where('is_recommend', '是')
                             ->where('status', '=', '1')
                             ->orderByRaw('RAND()')
                             ->take($number)
                             ->get();

        return $info;
    }

    /**
     * 处理选品库商品的逻辑
     *
     */
    public function getTaoKouLingFromFavoritesItem($favoritesItem) {
        // 判断数据库是否有淘口令
        if (empty($favoritesItem->tao_kou_ling)) {
            // 从淘宝获取淘口令
            $tkl = ['url'=>$favoritesItem->click_url, 
                    'text'=>"超值活动，惊喜多多，[".config('website.name')."]亲情推荐！"
                    ];
            $favoritesItem->tao_kou_ling = getTaoKouLing($tkl);
            $favoritesItemTaoKouLingStr = (string)$favoritesItem->tao_kou_ling;

            // 如果成功获取淘口令写入数据库
            if (strlen($favoritesItemTaoKouLingStr) > 4) {
                // 更新数据库
                $n = FavoritesItem::where('id', $favoritesItem->id)
                                  ->update(['tao_kou_ling'=>$favoritesItem->tao_kou_ling]);
                unset($n);
            } else {
                $favoritesItem->tao_kou_ling = '';
            }
        }

        return $favoritesItem->tao_kou_ling;
    }

    /**
     * 获取淘口令
     *
     */
    public function getTaoKouLing($link)
    {
        // $req = new WirelessShareTpwdCreateRequest;
        // $tpwd_param = new GenPwdIsvParamDto;
        // $tpwd_param->url=$link;
        // $tpwd_param->text="超值活动，惊喜活动多多，我爱你一万年网鼎力推荐！";
        // $tpwd_param->user_id=getenv('TAOBAO_ID');
        // $tpwd_param->user_id=config('taobao.id');
        // $req->setTpwdParam(json_encode($tpwd_param));
        // $resp = $this->c->execute($req);

        // return $resp->model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 判断是否是微信客户端访问的
     *
     */
    public function isWechat()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否是来自移动端的
     *
     */
    function isMobile()
    { 
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        } 
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
                ); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            } 
        } 
        return false;
    } 
}
