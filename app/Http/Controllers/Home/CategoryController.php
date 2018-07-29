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

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId, Request $request)
    {
        $categoryInfo = Category::where('id', $categoryId)
                      ->where('is_show', '是')
                      ->where('form', '<>', '0')
                      ->first(['id', 'name', 'adzone_id', 'form']);

        // 判断是否有对应的分类id是属于显示的状态,如果没有，返回到首页
        if (empty($categoryInfo)) {
            return redirect('/');
        } else {
            $categoryInfo = $categoryInfo->toArray();
        }

        $oldRequest              = $request->all();       // 本次的所有请求数据
        $pageSize                = 24;                    // 每页显示的商品数
        $pageInfo['title']       = $categoryInfo['name']; // 网页的title
        $pageInfo['description'] = $categoryInfo['name']; // 网站的description

        // 获取栏目的所有可以显示的信息
        $categoryAllInfo = Category::where('is_show', '是')
                                   ->where('form', '<>', '0')
                                   ->orderBy('order_self', 'asc')
                                   ->get()
                                   ->toArray();

        // 根据栏目所属的模型来调取对应栏目的商品数据
        switch ($categoryInfo['form']) {
            // 选品库列表
            case '1':
                // 选品库列表数据
                $favorites = $this->favorites($categoryId);

                // 选品库商品数据
                $favoritesItem = $this->favoritesItem($request, $pageSize, $categoryId);

                return view('home.FavoritesItem', ['pageInfo'        => $pageInfo,
                                                   'oldRequest'      => $oldRequest,
                                                   'categoryInfo'    => $categoryInfo,
                                                   'categoryAllInfo' => $categoryAllInfo,
                                                   'favorites'       => $favorites,
                                                   'favoritesItem'   => $favoritesItem
                                                  ]);
            
            // 定向招商的活动列表
            case '2':
                //
                break;
            
            // 精选优质商品清单（Excel）
            case '3':
                $couponInfo = $this->coupon($request, $pageSize);

                return view('home.Coupon', ['pageInfo'        => $pageInfo,
                                            'oldRequest'      => $oldRequest,
                                            'categoryInfo'    => $categoryInfo,
                                            'categoryAllInfo' => $categoryAllInfo,
                                            'couponInfo'      => $couponInfo
                                           ]);
            
            // 官方精选热推清单（最高佣金50%）
            case '4':
                //
                break;

            // 特殊情况，返回网站首页
            default:
                return redirect('/');
                break;
        }
    }

    /**
     * 获取优惠券的相关商品分类以及商品信息
     *
     */
    public function coupon(Request $request, $pageSize) {
        $cateStr = ''; // 标记分类的字符串
        $order = ''; // 标记排序的字符串

        if (!empty($request->cate)) {
            $cateStr = $request->cate;
            // 获取分类的名称
            $cc = new CouponCategory;
            $cc = $cc->where('self_where', '=', $request->cate)->first();
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }

        // 优惠券信息
        $c = new Coupon;
        $c = self::couponWhere($c, $cateStr);

        // 判断是否获取推荐商品的信息
        if (!empty($request->isrecommend) && $request->isrecommend == 'yes') {
            $c = $c->where('is_recommend', '=', '是');
        }
        // 判断优惠券是否过期
        $date = date('Y-m-d');
        $dateTime = strtotime($date);
        $date = date('Y-m-d H:i:s', $dateTime);
        $c = $c->where('yhq_end', '>=', $date);
        unset($date);
        unset($dateTime);

        $c = self::couponOrderBy($c, $order);
        $couponInfo['goods'] = $c->paginate($pageSize);

        // 优惠券分类信息
        $cc = new CouponCategory;
        $cc = $cc->where('is_show','=','是');
        $cc = $cc->orderBy('order', 'asc');
        $couponInfo['category'] = $cc->get();

        return $couponInfo;
    }



    /**
     * 根据商品栏目的id获取选品库的列表信息
     *
     */
    public function favorites($categoryId)
    {
        $favortesInfo = FavoritesCategory::where('category', $categoryId)
                                         ->where('is_show', '=', '是')
                                         ->orderBy('order_self', 'asc')
                                         ->get(['name', 'favorites_id'])
                                         ->toArray();

        return $favortesInfo;
    }

    /**
     * 根据商品栏目的id获取选品库的宝贝信息
     *
     */
    public function favoritesItem($request, $pageSize, $categoryId)
    {
        // 根据favoritesId来进行调用数据
        if (!empty($request->fid)) {
            // 只调用对应favorites_id的商品
            $favoritesIds = [$request->fid];
        } else {
            // 调用favorites_id集合的商品
            $favoritesIds = FavoritesCategory::where('category', $categoryId)
                                             ->where('is_show', '=', '是')
                                             ->get(['favorites_id'])
                                             ->toArray();
        }

        $favoritesItemInfo = FavoritesItem::whereIn('favorites_id', $favoritesIds)
                                          ->where('status', '=', 1);

        // 判断是否获取推荐商品的信息
        if (!empty($request->isrecommend) && $request->isrecommend == 'yes') {
            $favoritesItemInfo = $favoritesItemInfo->where('is_recommend', '=', '是');
        }

        $favoritesItemInfo = self::favoritesItemOrderBy($favoritesItemInfo, $request);
        $favoritesItemInfo = $favoritesItemInfo->paginate($pageSize);

        return $favoritesItemInfo;
    }

    /**
     * 根据拆分字符串查询的筛选条件
     *
     */
    public function couponWhere(Coupon $c, $str)
    {
        if (empty($str)) { // 没有出现分类的搜索情况
            return $c;
        }

        // 存在查找分类的情况
        $strArr = explode('or', $str);

        // 处理第一组商品
        $strArrOne = explode('and', $strArr[0]);
        foreach ($strArrOne as $str) {
            $arr = explode('like', $str);
            $c = $c->where($arr[0],'like',$arr[1]);
        }

        // 处理第二组商品
        if(count($strArr) == 2) {
            $strArrTwo = explode('and', $strArr[1]);
            $c = $c->orWhere(function ($query) use($strArrTwo) {
                foreach ($strArrTwo as $str) {
                    $arr = explode('like', $str);
                    $query->where($arr[0],'like',$arr[1]);
                }
            });
        }

        return $c;
    }

    /**
     * 优惠券排序的条件
     *
     */
    public function couponOrderBy($yhq, $order)
    {
        switch ($order) {
            case 'new':
                $results = $yhq->orderBy('yhq_begin','desc');
                break;
            
            case 'sup':
                $results = $yhq->orderBy('sales','asc');
                break;
            
            case 'sdown':
                $results = $yhq->orderBy('sales','desc');
                break;
            
            case 'pdown':
                $results = $yhq->orderBy('price_now','desc');
                break;
            
            case 'pup':
                $results = $yhq->orderBy('price_now','asc');
                break;

            case 'time';
                $results = $yhq->orderBy('yhq_end','desc');
                break;

            case 'rdown';
                $results = $yhq->orderBy('rate_sales','desc');
                break;

            case 'rup';
                $results = $yhq->orderBy('rate_sales','asc');
                break;
            
            default:
                $results = $yhq;
                break;
        }

        $results = $results->orderBy('id','desc');

        return $results;
    }

    /**
     * 选品库商品的排序条件
     *
     */
    public function favoritesItemOrderBy($favoritesItem, $request)
    {
        $order = '';

        if (!empty($request->order)) {
            $order = $request->order;
        }

        switch ($order) {
            // 无线实际售价的排序条件
            case 'zkFinalPriceWapDown':
                $results = $favoritesItem->orderBy('zk_final_price_wap', 'desc');
                return $results;
            
            case 'zkFinalPriceWapUp':
                $results = $favoritesItem->orderBy('zk_final_price_wap', 'asc');
                return $results;
            
            // 销量的排序条件
            case 'volumeDown':
                $results = $favoritesItem->orderBy('volume', 'desc');
                return $results;
            
            case 'volumeUp':
                $results = $favoritesItem->orderBy('volume', 'asc');
                return $results;
            
            default:
                return $favoritesItem;
        }
    }
}
