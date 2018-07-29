<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\FavoritesCategory;
use App\Model\FavoritesItem;
use App\Model\Category;
use App\Libraries\Taobaoke\Api;
use App\Libraries\Taobaoke\top\request\TbkUatmFavoritesItemGetRequest;


class FavoritesItemController extends Controller
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
     * 显示选品库宝贝入库的面板 
     *
     */
    public function dashboardIndex(Request $request)
    {     
        // 判断$favorites_id是否存在，如果不存在，返回到商品库列表的控制面板
        $n = FavoritesCategory::count();
        if (!$n) {
            return redirect()->route('favoritesDashboard');
        }
        unset($n);

        $pageSize   = 50;              // 每页多少数据
        $oldRequest = $request->all(); // 记录本次的所有请求

        if (!empty($request->pageSize)) {
            $pageSize = $request->pageSize;
        }

        // 处理全部入库的逻辑
            // 获取一个选品库列表的列表信息        
        $favoritesIdArr = FavoritesCategory::all(['favorites_id'])->toArray();
        $tag = false; // 标记是否继续循环
        $n   = count($favoritesIdArr); // 选品库列表的个数
        $key = 0; // 数组的下标
        $adzoneid = config('taobao.adzoneid');

        do {
            $favorites_id = (string)$favoritesIdArr[$key]['favorites_id'];
            $key++;

            // 获取淘宝数据库对应的5条商品信息
            $this->c = Api::api();
            $req = new TbkUatmFavoritesItemGetRequest;
            $req->setPlatform("1");
            $req->setPageSize("5");
            $req->setAdzoneId($adzoneid);
            $req->setFavoritesId($favorites_id);
            $req->setFields("num_iid,title,reserve_price,zk_final_price,provcity,shop_title,zk_final_price_wap");
            $infoAllInsert = $this->c->execute($req)->results;
            $infoAllInsert = simpleXMLElementToArray($infoAllInsert);

            if ($infoAllInsert == [] && $key < $n) { // 选品库列表不存在对应的商品
                $tag = true;
            } else {
                $tag = false;
            }
        } while ($tag);

        if (!empty($infoAllInsert['uatm_tbk_item']['num_iid'])) {

            $tmp = $infoAllInsert['uatm_tbk_item'];
            unset($infoAllInsert);
            $infoAllInsert['uatm_tbk_item'][0] = $tmp;
            unset($tmp);
        }

        // 释放无关的变量
        unset($tag);
        unset($key);
        unset($n);

        // 处理选品库宝贝信息入库面板的逻辑
        $favoritesCategoryInfo = FavoritesCategory::paginate($pageSize);
        $info = [];

        foreach ($favoritesCategoryInfo as $key=>$fc) {
            // 选品库列表的信息
            $info[$key]['favorites_id'] = $fc->favorites_id;
            $info[$key]['favorites_title'] = $fc->favorites_title;
            $info[$key]['category'] = $fc->category;

            // 选品库列表对应本地宝贝的总数量
            $info[$key]['local_total'] = FavoritesItem::where('favorites_id', '=', $fc->favorites_id)
                                                      ->count();

            // 选品库列表对应本地宝贝的今日更新的数量
            $todayTime = strtotime(date('Y-m-d')); // 当日的凌晨时分的时间戳
            $info[$key]['update_total'] = FavoritesItem::where('updated_at', '>', date('Y-m-d H:i:s', $todayTime))
                                                      ->where('favorites_id', '=', $fc->favorites_id)
                                                      ->count();

            // 本地无效宝贝的总数量
            $info[$key]['status_off_total'] = FavoritesItem::where('favorites_id', '=', $fc->favorites_id)
                                                           ->where('status', '=', '0')
                                                           ->count();

            // 选品库列表对应远程数据库的总量
            $this->c = Api::api();
            $req = new TbkUatmFavoritesItemGetRequest;
            $req->setPlatform("1");
            $req->setPageSize("1");
            $req->setAdzoneId($adzoneid);
            $req->setFavoritesId((string)$fc->favorites_id);
            $req->setFields("num_iid");
            $info[$key]['total_results'] = (integer)$this->c->execute($req)->total_results;
        }

        return view('admin.favoritesItem.dashboard', ['pageSize'=>$pageSize,
                                                      'infoAllInsert'=>$infoAllInsert['uatm_tbk_item'],
                                                      'infoAll'=>$info,
                                                      'oldRequest'=>$oldRequest,
                                                      'favoritesCategoryInfo'=>$favoritesCategoryInfo
                                                      ]);
    }

    /**
     * 显示选品库宝贝信息的面板
     *
     */
    public function goodsList(Request $request) {
        $pageSize    = 10;   // 每页显示的商品数
        $categoryId  = 0;    // 分类的id，默认为0
        $favoritesId = 0;    // 选品库分类列表, 默认为0
        $userType    = 'default';    // 平台的类型，0是淘宝，1是天猫
        $isRecommend = 'default';    // 商品是否被推荐
        $oldRequest  = $request->all();

        if (!empty($request->pageSize)) {
            $pageSize = $request->pageSize;
        } else {
            $oldRequest['pageSize'] = $pageSize;
        }

        if (!empty($request->categoryId)) {
            $categoryId = $request->categoryId;
        } else {
            $oldRequest['categoryId'] = $categoryId;
        }

        if (!empty($request->favoritesId)) {
            $favoritesId = $request->favoritesId;
        } else {
            $oldRequest['favoritesId'] = $favoritesId;
        }

        if (!empty($request->userType)) {
            $userType = $request->userType;
        } else {
            $oldRequest['userType'] = $userType;
        }

        if (!empty($request->isRecommend)) {
            $isRecommend = $request->isRecommend;
        } else {
            $oldRequest['isRecommend'] = $isRecommend;
        }

        // 获取栏目信息
        $categoryInfo = Category::where('form', '1')
                                ->orderBy('order_self', 'asc')
                                ->get(['id', 'name', 'adzone_id'])
                                ->toArray();

        // 获取栏目对应的选品库列表信息
        if ($categoryId == 0) {
            // 没有指定具体的栏目，不获取选品库列表信息
            $favoritesInfo = [];
        } else {
            // 有获取对应栏目的选品库列表信息
            $favoritesInfo = FavoritesCategory::where('category', $categoryId)
                                              ->orderBy('order_self', 'asc')
                                              ->get(['name', 'favorites_id', 'category'])
                                              ->toArray();
        }

        // 获取商品信息
        $fi = new FavoritesItem;
        $fi = $this->whereSelf($fi, $request);
        $fi = $this->orderBySelf($fi, $request);
        $goodsInfo = $fi->paginate($pageSize);


        return view('admin.favoritesItem.goodsList', ['goodsInfo'     => $goodsInfo,
                                                      'oldRequest'    => $oldRequest,
                                                      'categoryInfo'  => $categoryInfo,
                                                      'favoritesInfo' => $favoritesInfo
                                                     ]);
    }

    /**
     * 根据选品库列表的所有ID来更新选品库宝贝的信息的逻辑
     *
     */
    public function updateByFavoritesIdsAll() {
        $n = FavoritesCategory::count();
        // 判断$favorites_id是否存在，如果不存在，返回到商品库列表的控制面板
        if (!$n) {
            return redirect()->route('favoritesDashboard');
        }

        // 选品库列表的favorites_id的集合
        $favorites_ids = FavoritesCategory::all(['favorites_id']);

        // 根据favorites_id逐个进行更新入库
        foreach ($favorites_ids as $key=>$favorite) {
          $results[$key] = $this->updateByFavoritesIdAction($favorite->favorites_id);
        }

        // 汇总更新的结果
        $createNo = 0;
        $updateNo = 0;
        foreach ($results as $result) {
          $createNo += $result['createNo'];
          $updateNo += $result['updateNo'];
        }
        unset($results);

        if ($createNo + $updateNo) {
            return back()->with('updateByFavoritesIdsAllStatus', '成功更新'.$updateNo.'条商品信息；成功创建'.$createNo.'条商品信息。');
        } else {
            return redirect()->route('favoritesItemDashboard')->withErrors('淘宝数据库中没有对应的商品信息或者选品库列表没有分配栏目！');
        }
    }

    /**
     * 根据选品库列表的ID集合来更新选品库宝贝的信息的逻辑
     *
     */
    public function updateByFavoritesIdsSome(Request $request) {
        $n = FavoritesCategory::count();
        // 判断$favorites_id是否存在，如果不存在，返回到商品库列表的控制面板
        if (!$n) {
            return redirect()->route('favoritesDashboard');
        }

        // 选品库列表的favorites_id的集合
        $favorites_ids = $request->ids;
        
        // 如果集合为空，则回到控制面板
        if (empty($favorites_ids)) {
          return back()->withErrors('请先选择选品库列表，再进行更新！');
        }

        // 根据favorites_id逐个进行更新入库
        foreach ($favorites_ids as $key=>$favorites_id) {
          $results[$key] = $this->updateByFavoritesIdAction($favorites_id);
        }

        // 汇总更新的结果
        $createNo = 0;
        $updateNo = 0;
        foreach ($results as $result) {
          $createNo += $result['createNo'];
          $updateNo += $result['updateNo'];
        }
        unset($results);

        if ($createNo + $updateNo) {
            return back()->with('updateByFavoritesIdsSomeStatus', '成功更新'.$updateNo.'条商品信息；成功创建'.$createNo.'条商品信息。');
        } else {
            return redirect()->route('favoritesItemDashboard')->withErrors('淘宝数据库中没有对应的商品信息或者选品库列表没有分配栏目！');
        }
    }

    /**
     * 根据选品库列表的单个ID来更新选品库宝贝的信息的逻辑
     *
     */
    public function updateByFavoritesId(Request $request) {
        $favorites_id = $request->favoritesId;

        // 判断$favorites_id是否存在
        if (empty($favorites_id)) {
            return redirect()->route('favoritesItemDashboard');
        }

        $result = $this->updateByFavoritesIdAction($favorites_id);

        if ($result['createNo'] + $result['updateNo']) {
            return back()->with('updateByFavoritesIdStatus', '成功更新'.$result['updateNo'].'条商品信息；成功创建'.$result['createNo'].'条商品信息。');
        } else {
            return redirect()->route('favoritesItemDashboard')->withErrors('淘宝数据库中没有对应的商品信息或者选品库列表没有分配栏目！');
        }
    }

    /**
     * 删除所有的商品信息
     *
     */
    public function deleteAll(Request $request)
    {
        if (empty($request->confirmCode) || ($request->confirmCode != 'alldelte002')) {
          return back()->with('confirmCodeStatus', '删除确认码不一致，请重新输入！');
        }

        // 清空商品信息
        $n = FavoritesItem::truncate();

        if ($n) {
            return back()->with('deleteAllstatus','成功删除所有商品信息！');
        } else {
            return back();
        }
    }

    /**
     * 删除特定日期的所有选品库的宝贝信息
     *
     */
    public function deleteFromDate(Request $request)
    {
        $dateBegin = $request->datebegin; // 起始日期
        $dateBegin = strtotime($dateBegin);
        $dateBegin = date('Y-m-d H:i:s',$dateBegin);

        $dateEnd = $request->dateend; // 结束日期
        $dateEnd = strtotime($dateEnd)+60*60*24-1;
        $dateEnd = date('Y-m-d H:i:s',$dateEnd);

        // 验证起始日期
        if (empty($dateBegin)) {
            return back()->with('status', 'dateBeginEmpty');
        }
        // 验证截止日期
        if (empty($dateEnd)) {
            return back()->with('status', 'dateEndEmpty');
        }

        $n = FavoritesItem::whereBetween('created_at', [$dateBegin, $dateEnd])->delete();

        if ($n) {
            return back()->with('deleteFromDateStatus','成功删除'.$n.'条商品信息。');
        } else {
            return back()->with('deleteFromDateStatus','删除0条商品信息。');
        }
    }

    /**
     * 根据特定的favorites_id删除商品信息
     *
     */
    public function deleteByFavoritesId(Request $request)
    {
        $favorites_id = $request->favoritesId;
        if (empty($favorites_id)) {
          return back()->route('favoritesItemDashboard');
        }

        $n = FavoritesItem::where('favorites_id', '=', $favorites_id)->delete();

        if ($n) {
            return back()->with('list','成功删除'.$n.'条商品信息。');
        } else {
            return back()->with('list','删除0条商品信息。');
        }
    }

    /**
     * 根据favorites_id的集合删除商品信息
     *
     */
    public function deleteByFavoritesIds(Request $request)
    {
        // 选品库列表的favorites_id的集合
        $favorites_ids = $request->ids;
        // 如果集合为空，则回到控制面板
        if (empty($favorites_ids)) {
          return back()->withErrors('请先选择选品库列表，再进行删除操作！');
        }

        $n = FavoritesItem::whereIn('favorites_id', $favorites_ids)->delete();

        if ($n) {
            return back()->with('list','成功删除'.$n.'条商品信息。');
        } else {
            return back()->with('list','删除0条商品信息。');
        }
    }

    /**
     * 根据favorites_id的集合删除无效的商品信息
     *
     */
    public function deleteStatusOff(Request $request)
    {
        // 选品库列表的favorites_id的集合
        $favorites_ids = $request->ids;
        // 如果集合为空，则回到控制面板
        if (empty($favorites_ids)) {
          return back()->withErrors('请先选择选品库列表，再进行删除操作！');
        }

        $n = FavoritesItem::whereIn('favorites_id', $favorites_ids)
                          ->where('status', '=', '0')
                          ->delete();

        if ($n) {
            return back()->with('list','成功删除'.$n.'条失效的商品信息。');
        } else {
            return back()->with('list','删除0条失效的商品信息。');
        }
    }

    /**
     * 根据favorites_id删除无效的商品信息
     *
     */
    public function deleteStatusOffFavoriteId(Request $request)
    {
        // 选品库列表的favorites_id的集合
        $favorites_id = $request->favoritesId;
        // 如果集合为空，则回到控制面板
        if (empty($favorites_id)) {
          return back();
        }

        $n = FavoritesItem::where('favorites_id', $favorites_id)
                          ->where('status', '=', '0')
                          ->delete();

        if ($n) {
            return back()->with('list','成功删除'.$n.'条失效的商品信息。');
        } else {
            return back()->with('list','删除0条失效的商品信息。');
        }
    }

    /**
     * 根据favorites_id删除选中今日无更新的宝贝信息
     *
     */
    public function deleteUpdateNoToday(Request $request)
    {
        // 选品库列表的favorites_id的集合
        $favorites_ids = $request->ids;
        // 如果集合为空，则回到控制面板
        if (empty($favorites_ids)) {
          return back()->withErrors('请先选择选品库列表，再进行删除操作！');
        }

        $tmp = strtotime(date('Y-m-d'));
        $date = date('Y-m-d H:i:s', $tmp); // 今日凌晨的时间

        $n = FavoritesItem::whereIn('favorites_id', $favorites_ids)
                          ->where('updated_at', '<', $date)
                          ->delete();

        if ($n) {
            return back()->with('list','成功删除'.$n.'条今日无更新的商品信息。');
        } else {
            return back()->with('list','删除0条今日无更新的商品信息。');
        }
    }

    /**
     * 根据favorites_id删除选中今日无更新的宝贝信息
     *
     */
    public function deleteUpdateNoTodayByFid(Request $request)
    {
        // 选品库列表的favorites_id的集合
        $favorites_id = $request->favoritesId;
        // 如果集合为空，则回到控制面板
        if (empty($favorites_id)) {
          return back()->withErrors('请先选择选品库列表，再进行删除操作！');
        }

        $tmp = strtotime(date('Y-m-d'));
        $date = date('Y-m-d H:i:s', $tmp); // 今日凌晨的时间

        $n = FavoritesItem::where('favorites_id', $favorites_id)
                          ->where('updated_at', '<', $date)
                          ->delete();

        if ($n) {
            return back()->with('list','成功删除'.$n.'条今日无更新的商品信息。');
        } else {
            return back()->with('list','删除0条今日无更新的商品信息。');
        }
    }

    /**
     * 根据选品库的单个ID来更新选品库宝贝的信息getFavoritesItems的逻辑
     *
     */
    public function updateByFavoritesIdAction($favorites_id)
    {
        $favorites_id = $favorites_id;
        $page_size    = 50;    // 页大小
        $page_no      = 1;     // 第几页
        $plat_form    = 1;     // 平台链接
        $tag          = false; // 标记是否继续循环
        $updateNo     = 0;     // 记录商品宝贝更新的数据量
        $createNo     = 0;     // 记录商品宝贝新增的数据量

        if (empty($favorites_id)) {
            return redirect()->route('favoritesItemDashboard');
        }

        //  如果数据库中不存在对应id的选品库宝贝列表信息，则返回对应的数组
        $n = FavoritesCategory::where('favorites_id', $favorites_id)->count();
        if (!$n) {
            return ['updateNo'=>$updateNo, 'createNo'=>$createNo];
        }
        
        // 根据选品库列表的id从淘宝服务器获取商品宝贝信息
        // 批量更新入库
        do
        {
            // 获取宝贝信息的数据
            $itemsInfo = $this->getFavoritesItems($favorites_id, $page_size, $page_no, $plat_form);

            // 如果出现没有分配栏目的选品库列表，则该列表的信息不进行入库处理
            if ($itemsInfo === 'stop') {
                break;
            }

            $total_results = (string)$itemsInfo->total_results; // 淘宝数据库宝贝信息的总数

            if (!$total_results) {
                return ['updateNo'=>$updateNo, 'createNo'=>$createNo];
            }

            // 将从淘宝获取到的宝贝信息转换数组
            $itemsInfoArr = simpleXMLElementToArray($itemsInfo);

            // 如果是商品是一维数组，则转变成二位数组
            if (!empty($itemsInfoArr['results']['uatm_tbk_item']['num_iid'])) {
                $tmp = $itemsInfoArr['results']['uatm_tbk_item'];
                unset($itemsInfoArr['results']['uatm_tbk_item']);
                $itemsInfoArr['results']['uatm_tbk_item'][0] = $tmp;
                unset($tmp);
            }

            // 将数据转换成写入数据库的格式
            $uatmTbkItem = $itemsInfoArr['results']['uatm_tbk_item'];
            foreach ($uatmTbkItem as $key=>$items) {
                // 把favorites_id加入到数组中
                $uatmTbkItem[$key]['favorites_id'] = $favorites_id;

                // 处理small_images数组
                if (!empty($uatmTbkItem[$key]['small_images'])) {
                    $uatmTbkItem[$key]['small_images'] = serialize($uatmTbkItem[$key]['small_images']['string']);
                } else {
                    $uatmTbkItem[$key]['small_images'] = '';
                }

                // 对数据进行验证，防止出现空数组的情况,如果出现空数组的情况，则将空数组变为空字符串
                $uatmTbkItem[$key] = $this->validateEmtyArr($uatmTbkItem[$key]);

                // 计算单个商品的佣金
                $money                      = $items['zk_final_price_wap']*$items['tk_rate']/100;
                $uatmTbkItem[$key]['money'] = round($money, 2);
                unset($money);

                // 检查数据库是否有相应的宝贝
                $n = FavoritesItem::where('num_iid', $uatmTbkItem[$key]['num_iid'])->count();

                if ($n) {
                    // 数据库存在商品信息
                    FavoritesItem::where('num_iid', $uatmTbkItem[$key]['num_iid'])
                                 ->update($uatmTbkItem[$key]);
                    $updateNo++;
                } else {
                    // 数据库中不存在商品信息
                    $createNo++;
                    FavoritesItem::create($uatmTbkItem[$key]);
                }
            }

            // 判断是否继续执行循环
            if ($total_results > $page_size*$page_no) {
                $tag = true;
                $page_no++;
            } else {
                $tag = false;
            }
        } while ($tag);

        return ['updateNo'=>$updateNo, 'createNo'=>$createNo];
    }

    /**
     * 根据选品库列表的id从淘宝服务器获取商品宝贝信息，并进行更新
     * 四个参数分别是 选品库列表的id，页大小，第几页，链接形式
     *
     */
    public function getFavoritesItems($favorites_id, $page_size = '20', $page_no = '1', $plat_form = '1')
    {
        // 获取栏目的id
        $category_id = FavoritesCategory::where('favorites_id', $favorites_id)
                                        ->first(['category'])
                                        ->category;

        // 如果遇到没有分配栏目的选品库列表时，停止选品库商品的入库
        if (empty($category_id)) {
            return 'stop';
        }

        // 根据栏目的id获取推广位id
        $adzone_id = Category::where('id', $category_id)
                             ->first(['adzone_id'])
                             ->adzone_id;

        // 从淘宝服务器获取宝贝信息
        $req = new TbkUatmFavoritesItemGetRequest;

        $req->setPlatform((string)$plat_form);  // 链接形式：1：PC，2：无线
        $req->setPageSize((string)$page_size); // 页大小
        $req->setAdzoneId((string)$adzone_id);
        $req->setFavoritesId((string)$favorites_id);
        $req->setPageNo((string)$page_no);    // 第几页
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,click_url,nick,seller_id,volume,tk_rate,zk_final_price_wap,shop_title,event_start_time,event_end_time,type,status,category,coupon_click_url,coupon_end_time,coupon_info,coupon_start_time,coupon_total_count,oupon_remain_count");
        $itemsInfo = $this->c->execute($req);

        return $itemsInfo;
    }

    /**
     * 验证数组中的空数组，如果出现空数组，则将空数组变为空字符串
     *
     */
    public function validateEmtyArr($array)
    {
        foreach ($array as $key => $value) {
          if ($value === [])
            $array[$key] = '';
        }

        return $array;
    }

    /**
     * 查询选品库商品的自定义条件
     *
     */
    public function whereSelf($fi, $request) {
        // 选品库列表条件过滤
        if (!empty($request->categoryId) && $request->categoryId != 0) { // 存在选定特定栏目的情况
            // 获取栏目对应的favorites_id的集合
            $favoritesIds = FavoritesCategory::where('category', $request->categoryId)
                                             ->get(['favorites_id'])
                                             ->toArray();

            // 获取favorites_id对应的数组
            $favoritesIdsArr = [];
            foreach ($favoritesIds as $favorites) {
                $favoritesIdsArr[] = $favorites['favorites_id']; 
            }

            if (!empty($request->favoritesId) && $request->favoritesId != 0) {
                // 存在选定特定选品库列表的情况
                if (in_array($request->favoritesId, $favoritesIdsArr)) {
                    //　选特定选品库列表的情况
                    $fi = $fi->where('favorites_id', $request->favoritesId);
                } else {
                    // 选择栏目对应的所有favorites_id情况
                    $fi = $fi->whereIn('favorites_id', $favoritesIdsArr);
                }
            } else {
                // 只选择特定栏目的情况
                $fi = $fi->whereIn('favorites_id', $favoritesIdsArr);
            }
        }

        // 平台条件过滤
        if (!empty($request->userType) && $request->userType !== 'default') {
            $fi = $fi->where('user_type', $request->userType-1);
        }

        // 是否推荐条件过滤
        if (!empty($request->isRecommend) && $request->isRecommend != 'default') {
            $fi = $fi->where('is_recommend', $request->isRecommend);
        }

        // 无线售价条件过滤
        if (!empty($request->zkFinalPriceWapMin)) {
            $fi = $fi->where('zk_final_price_wap', '>=', $request->zkFinalPriceWapMin);
        }
        if (!empty($request->zkFinalPriceWapMax)) {
            $fi = $fi->where('zk_final_price_wap', '<=', $request->zkFinalPriceWapMax);
        }

        // 折扣价格条件过滤
        if (!empty($request->zkFinalPriceMin)) {
            $fi = $fi->where('zk_final_price', '>=', $request->zkFinalPriceMin);
        }
        if (!empty($request->zkFinalPriceMax)) {
            $fi = $fi->where('zk_final_price', '<=', $request->zkFinalPriceMax);
        }

        // 一口价格条件过滤
        if (!empty($request->reservePriceMin)) {
            $fi = $fi->where('reserve_price', '>=', $request->reservePriceMin);
        }
        if (!empty($request->reservePriceMax)) {
            $fi = $fi->where('reserve_price', '<=', $request->reservePriceMax);
        }

        // 佣金条件过滤
        if (!empty($request->moneyMin)) {
            $fi = $fi->where('money', '>=', $request->moneyMin);
        }
        if (!empty($request->moneyMax)) {
            $fi = $fi->where('money', '<=', $request->moneyMax);
        }

        // 销量条件过滤
        if (!empty($request->volumeMin)) {
            $fi = $fi->where('volume', '>=', $request->volumeMin);
        }
        if (!empty($request->volumeMax)) {
            $fi = $fi->where('volume', '<=', $request->volumeMax);
        }

        // 收入比例条件过滤
        if (!empty($request->tkRateMin)) {
            $fi = $fi->where('tk_rate', '>=', $request->tkRateMin);
        }
        if (!empty($request->tkRateMax)) {
            $fi = $fi->where('tk_rate', '<=', $request->tkRateMax);
        }

        // 搜索词条件过滤
        if (!empty($request->q)) {
            //将字符串按照空格来分割成数组
            $qarr = explode(' ', $request->q);
            $qarr = array_filter($qarr);
            foreach ($qarr as $key => $value) {
                $qarr[$key] = '%'.$value.'%';
            }
            $qarr = array_values($qarr);

            foreach ($qarr as $key => $value) {
                $fi = $fi->where('title','like',$value);
            }
        }

        return $fi;
    }

    /**
     * 查询选品库商品的自定义条件
     *
     */
     public function orderBySelf($fi, $request) {
        if (!empty($request->orderud)) {
            switch ($request->orderud) {
                // 一口价格
                case 'reservePriceAsc':
                    $fi = $fi->orderBy('reserve_price', 'asc');
                    break;
                case 'reservePriceDesc':
                    $fi = $fi->orderBy('reserve_price', 'desc');
                    break;
                
                // 折扣价格
                case 'zkFinalPriceAsc':
                    $fi = $fi->orderBy('zk_final_price', 'asc');
                    break;
                case 'zkFinalPriceDesc':
                    $fi = $fi->orderBy('zk_final_price', 'desc');
                    break;

                // 无线售价
                case 'zkFinalPriceWapAsc':
                    $fi = $fi->orderBy('zk_final_price_wap', 'asc');
                    break;
                case 'zkFinalPriceWapDesc':
                    $fi = $fi->orderBy('zk_final_price_wap', 'desc');
                    break;

                // 收入比例
                case 'tkRateAsc':
                    $fi = $fi->orderBy('tk_rate', 'asc');
                    break;
                case 'tkRateDesc':
                    $fi = $fi->orderBy('tk_rate', 'desc');
                    break;

                // 佣金
                case 'moneyAsc':
                    $fi = $fi->orderBy('money', 'asc');
                    break;
                case 'moneyDesc':
                    $fi = $fi->orderBy('money', 'desc');
                    break;

                // 月销量
                case 'volumeAsc':
                    $fi = $fi->orderBy('volume', 'asc');
                    break;
                case 'volumeDesc':
                    $fi = $fi->orderBy('volume', 'desc');
                    break;

                default:
                    break;
            }
        }

        return $fi;
     }

    /**
     * 根据商品的id来删除商品信息
     *
     */
     public function deleteItemById (Request $request) {
        $id = $request->id;

        if (empty($id)) {
            return back();
        }

        // 根据id执行删除操作
        $n = FavoritesItem::where('id', $id)
                          ->delete();

        // 判断是否删除成功
        if ($n) {
            return back()->with('status', '成功删除ID为'.$id.'的信息！');
        } else {
            return back()->withErrors('删除id为'.$id.'的信息失败！');
        }
     }

    /**
     * 根据商品的id来删除商品信息
     *
     */
     public function deleteItemByIds (Request $request) {
        $ids = $request->goodsIdArray;

        if (empty($ids)) {
            return back();
        }

        // 根据id执行删除操作
        $n = FavoritesItem::whereIn('id', $ids)
                          ->delete();

        // 判断是否删除成功
        if ($n) {
            return back()->with('status', '成功'.$n.'条信息！');
        } else {
            return back()->withErrors('删除信息失败！');
        }
     }

    /**
     * 根据商品的id来推荐商品信息
     *
     */
     public function recommendItemById (Request $request) {
        $id = $request->id;

        if (empty($id)) {
            return back();
        }

        // 根据id执行更新操作
        $n = FavoritesItem::where('id', $id)
                          ->update(['is_recommend'=>'是']);

        // 判断是否更新成功
        if ($n) {
            return back()->with('status', '成功推荐id为'.$id.'的信息！');
        } else {
            return back()->withErrors('推荐信息失败！');
        }
     }

    /**
     * 根据商品的id的集合来推荐商品信息
     *
     */
     public function recommendItemByIds (Request $request) {
        $ids = $request->goodsIdArray;

        if (empty($ids)) {
            return back();
        }

        // 根据id执行更新操作
        $n = FavoritesItem::whereIn('id', $ids)
                          ->update(['is_recommend'=>'是']);

        // 判断是否更新成功
        if ($n) {
            return back()->with('status', '成功推荐'.$n.'条信息！');
        } else {
            return back()->withErrors('推荐信息失败！');
        }
     }

    /**
     * 根据商品的id来取消推荐商品信息
     *
     */
     public function notRecommendItemById (Request $request) {
        $id = $request->id;

        if (empty($id)) {
            return back();
        }

        // 根据id执行更新操作
        $n = FavoritesItem::where('id', $id)
                          ->update(['is_recommend'=>'否']);

        // 判断是否更新成功
        if ($n) {
            return back()->with('status', '成功取消推荐id为'.$id.'的信息！');
        } else {
            return back()->withErrors('取消推荐信息失败！');
        }
     }

    /**
     * 根据商品的id的集合来取消推荐商品信息
     *
     */
     public function notRecommendItemByIds (Request $request) {
        $ids = $request->goodsIdArray;

        if (empty($ids)) {
            return back();
        }

        // 根据id执行更新操作
        $n = FavoritesItem::whereIn('id', $ids)
                          ->update(['is_recommend'=>'否']);

        // 判断是否更新成功
        if ($n) {
            return back()->with('status', '成功取消推荐'.$n.'条信息！');
        } else {
            return back()->withErrors('取消推荐信息失败！');
        }
     }
}
