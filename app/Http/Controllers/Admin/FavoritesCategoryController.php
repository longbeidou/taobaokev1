<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\FavoritesCategory;
use App\Model\FavoritesItem;
use App\Model\Category;
use App\Libraries\Taobaoke\Api;
use App\Libraries\Taobaoke\top\request\TbkUatmFavoritesGetRequest;


class FavoritesCategoryController extends Controller
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
     * 选品库列表的控制面板，将选品库列表信息进行入库的操作
     * 
     */
    public function dashboard(Request $request)
    {
        $pageSize = '5'; // 每页多少数据
        $pageno = $request->pageno; // 第几页

        if (empty($pageno)) {
            $pageno = 1;
        }

        //　调用选品库列表的api
        $req = new TbkUatmFavoritesGetRequest;
        $req->setPageNo($pageno);
        $req->setPageSize($pageSize);
        $req->setFields("favorites_title,favorites_id,type");
        $resp = $this->c->execute($req);

        $num = (integer)$resp->total_results; // 总的选品库列表数量
        $resp = (array)$resp;

        // 查看数据库的选品库列表信息，确定是否需要删除
        $oldRequest = $request->all();
        $pageSize2 = 10; // 每页显示的商品数量
        if (!empty($request->pageSize2)) {
            $pageSize2 = $request->pageSize2;
        }

        $fc = new FavoritesCategory;
        $fc = $fc->orderBy('updated_at', 'desc');
        $info2 = $fc->paginate($pageSize2);

        // 获取每个选品库列表id共有多少个商品的信息
        $info2Arr = $info2->toArray();
        $count = []; // 记录每个选品库列表对应商品的总数
        foreach ($info2Arr['data'] as $value) {
            $count[$value['favorites_id']] = FavoritesItem::where('favorites_id', $value['favorites_id'])->count();
        }

        return view('admin.favorites.dashboard', ['pageno'=>$pageno,
                                                  'pageSize'=>$pageSize,
                                                  'favoritesInfo'=>$resp['results'],
                                                  'num'=>$num,
                                                  'info2'=>$info2,
                                                  'count'=>$count,
                                                  'oldRequest'=>$oldRequest,
                                                  'pageSize2'=>$pageSize2
                                                  ]);
    }

    /**
     * 在控制面板显示选品库列表
     *
     */
    public function dashboardList(Request $request)
    {
        $pageNumber = 10; // 每页显示的商品数量
        if (!empty($request->pageNumber)) {
            $pageNumber = $request->pageNumber;
        }

        $oldRequest = $request->all();

        // 获取选品库列表的信息
        $fc = new FavoritesCategory;
        $fc = $this->favoritesOrderBy($request, $fc);
        $info = $fc->paginate($pageNumber);

        // 获取栏目的信息
        $categoryInfo = Category::where('form', '1')
                                ->orderBy('order_self', 'asc')
                                ->get(['id', 'name']);

        $categoryList = []; // 用于前台显示栏目列表的数组
        
        foreach ($categoryInfo as $value) {
            $categoryList[$value->id] = $value->name;
        }

        return view('admin.favorites.dashboardList', ['info'=>$info, 
                                                      'oldRequest'=>$oldRequest, 
                                                      'pageNumber'=>$pageNumber,
                                                      'categoryList'=>$categoryList
                                                      ]);
    }

    /**
     * 根据栏目显示选品库列表
     *
     */
    public function fList(Request $request) {
        $categoryId   = 0;  // 栏目的id，用于记录当前查找的栏目id，默认是0
        $categoryIds  = []; // 栏目的ID集合
        $categoryName = []; // 栏目id对应的name集合
        $pageSize     = 10; // 每页显示的选品库列表数量
        $favoritesIds = []; // 选品库列表的favorites_id集合
        $goodsTotal   = []; // 选品库列表的favorites_id对应的商品总数

        $oldRequest   = $request->all();

        // 获取栏目信息
        $category = Category::where('form', '1')
                            ->orderBy('order_self', 'asc')
                            ->get()
                            ->toArray();

        foreach ($category as $c) {
            $categoryIds[]          = $c['id'];
            $categoryName[$c['id']] = $c['name'];
        }

        // 判断传入的栏目id是否有效，如果有效则进行赋值操作
        if (!empty($request->categoryId) && in_array($request->categoryId, $categoryIds)) {
            $categoryId = $request->categoryId;
        }

        // 判断传入的页面选品库列表数量是否有效，如果有效则进行赋值操作
        if (!empty($request->pageSize)) {
            $pageSize = $request->pageSize;
        }        

        // 获取选品库列表的信息
        $fc = new FavoritesCategory;
        if ($categoryId == 0) {
            // 所有栏目的选品库列表信息
            $fc = $fc->whereIn('category', $categoryIds);
        } else {
            // 对应栏目的选品库列表信息
            $fc = $fc->where('category', $categoryId);
        }
        $fc      = $this->favoritesOrderBy($request, $fc);
        $info    = $fc->paginate($pageSize);
        $infoArr = $info->toArray();
        
        // 获取选品库列表的favorites_id的集合
        foreach ($infoArr['data'] as $favorites) {
            $favoritesIds[] = $favorites['favorites_id'];
        }

        // 选品库列表的favorites_id对应的商品总数
        foreach ($favoritesIds as $favorites_id) {
            $goodsTotal[$favorites_id] = FavoritesItem::where('favorites_id', $favorites_id)
                                                      ->count();
        }

        return view('admin.favorites.list', ['info'         => $info, 
                                             'oldRequest'   => $oldRequest, 
                                             'pageSize'     => $pageSize,
                                             'category'     => $category,
                                             'categoryId'   => $categoryId,
                                             'categoryName' => $categoryName,
                                             'goodsTotal'   => $goodsTotal
                                             ]);
    }

    /**
     * 将选品库列表全部入库
     *
     */
    public function insertAll()
    {
        $req = new TbkUatmFavoritesGetRequest;
        $req->setPageSize("200");
        $req->setFields("favorites_title,favorites_id,type");
        $resp = $this->c->execute($req);

        $favoritesInfo = simpleXMLElementToArray($resp->results);

        $tag = 0;
        // 执行批量入库操作
        foreach ($favoritesInfo['tbk_favorites'] as $favorites) {
            // 检查数据库是否有对应的选品库列表信息
            $n = FavoritesCategory::where('favorites_id', $favorites['favorites_id'])->count();
            if ($n) {
                // 存在数据，则进行更新
                $category = FavoritesCategory::where('favorites_id', $favorites['favorites_id'])
                                             ->update($favorites);

                $tag++;
            } else {
                // 不存在数据，进行插入操作
                FavoritesCategory::create($favorites);
                $tag++;
            }
        }
        
        if ($tag) {
            // 添加成功
            return redirect()->route('favoritesDashboard')->with('insertAllStatus', 'success');
        } else {
            // 添加失败
            return redirect()->route('favoritesDashboard')->with('insertAllStatus', 'faild');
        }
    }

    /**
     * 将选品库列表的数据全部删除
     *
     */
    public function deltAll(Request $request)
    {
        $confirmCode = $request->confirmCode; // 删除确认码

        // 判读删除确认码是否存在
        if (empty($confirmCode)) {
            return back()->with('status', 'confireCodeWrong');
        } elseif ($confirmCode != 'alldelte001') {
            return back()->with('status', 'confireCodeWrong');
        }

        $yhq = new FavoritesCategory;
        $n = $yhq->truncate();

        if ($n) {
            return back()->with('status','delteAllSuccess');
        } else {
            return back()->with('status','delteAllFaild');
        }
    }

    /**
     *  按日期删除选聘库列表
     */
    public function deletetDate(Request $request) {
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

        $fc = new FavoritesCategory;
        $fc = $fc->whereBetween('created_at', [$dateBegin, $dateEnd]);
        $n = $fc->delete();

        if ($n) {
            return back()->with('status','delteDateSuccess');
        } else {
            return back()->with('status','delteDateFailed');
        }
    }

    /**
     * 根据选品库列表的ID来进行删除
     *
     */
    public function deleteId(Request $request)
    {
        if (!empty($request->id)) {
            $fc = new FavoritesCategory;
            $n = $fc->where('id', $request->id)->delete();
            if ($n) {
                return back()->with('status', 'delidsuccess');
            } else {
                return back()->with('status', 'delidfailed');
            }
        } else {
            return redirect()->route('favoritesDashboardList');
        }
    }

    /**
     * 根据选品库列表的ID集合来进行删除
     *
     */
    public function deleteIds(Request $request)
    {
        $ids = $request->ids;

        if (!empty($ids)) {
            $fc = new FavoritesCategory;
            $n = $fc->whereIn('id', $ids)->delete();
            if ($n) {
                return back()->with('status', 'delidssuccess');
            } else {
                return back()->with('status', 'delidsfailed');
            }
        } else {
            return redirect()->route('favoritesDashboardList');
        }
    }

    /**
     * 将选品库列表根据id来进行编辑
     *
     */
    public function editId(Request $request)
    {
        $id = $request->id; // 选品库的id

        if (empty($id)) {
            return redirect()->route('dashboard');
        }

        $fc = new FavoritesCategory;
        $info = $fc->where('id', $id)->first();
        
        // 数据库中不存在对应ID的数据信息
        if (empty($info->id)) {
            return redirect()->route('favoritesList');
        }
        return view('admin.favorites.edit', ['info'=>$info]);
    }

    /**
     * 根据ID进行修改
     */
    public function updateById(Request $request)
    {
        $name = $request->name;
        $id = $request->id;
        $orderSelf = $request->order_self;
        $isShow = $request->is_show;

        $n = 0; // 标记被更新的数据

        // 更新数据库
        $fc = new FavoritesCategory;
        $fc = $fc->where('id', $id);
        $n += $fc->update(['name'=>$name, 'order_self'=>$orderSelf, 'is_show'=>$isShow]);

        
        if ($n) { // 编辑成功
            return redirect()->route('favoritesList')->with('listStatus','对id为'.$id.'的列表信息修改成功！');
        } else { // 编辑失败
            return redirect()->route('favoritesList')->withErrors('对id为'.$id.'的列表信息修改失败！');
        }
    }

    /**
     * 根据ID集合来批量更新排序字段
     */
    public function updateOrder(Request $request)
    {
        $orderInfo = $request->order;
        $from = $request->from;

        $n = 0; // 标记被更新的数据

        // 更新数据库
        foreach ($orderInfo as $id => $order) {
            $fc = new FavoritesCategory;
            $fc = $fc->where('id', $id);
            $n += $fc->update(['order_self'=>$order]);
        }
        
        if ($n) { // 编辑成功
            return redirect()->route('favoritesList')->with('listStatus','成功更新'.$n.'条信息的自定义排序。');
        } else { // 编辑失败
            return redirect()->route('favoritesList')->withErrors('批量更新自定义排序失败，请重新操作！');
        }
    }

    /**
     * 将批量更新选品库列表
     */
    public function update(Request $request)
    {
        $info = $request->all();
        $update = $info['list'];

        $n = 0; // 标记被更新的数据

        // 批量更新数据库
        foreach ($update as $id => $list) {
            $fc = new FavoritesCategory;
            $fc = $fc->where('id', $id);
            $listDate = ['name'=>$list[0], 'category'=>$list[1], 'is_show'=>$list[2]];
            $n += $fc->update($listDate);
        }
        
        if ($n) {
            return back()->with('status','updateSuccess');
        } else {
            return back()->with('status','updateFailed');
        }
    }

    /**
     * 选品库列表排序
     */
    public function favoritesOrderBy($request, $fc)
    {
        // 根据分类的自定义名称排序
        if (!empty($request->order) && $request->order == 'nameasc') {
            return $fc = $fc->orderBy('name', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'namedesc') {
            return $fc = $fc->orderBy('name', 'desc');
        }

        // 根据选品库的类型排序
        if (!empty($request->order) && $request->order == 'typeasc') {
            return $fc = $fc->orderBy('type', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'typedesc') {
            return $fc = $fc->orderBy('type', 'desc');
        }

        // 根据选品组名称排序
        if (!empty($request->order) && $request->order == 'favoritestitleasc') {
            return $fc = $fc->orderBy('favorites_title', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'favoritestitledesc') {
            return $fc = $fc->orderBy('favorites_title', 'desc');
        }

        // 根据选聘组所属的栏目id排序
        if (!empty($request->order) && $request->order == 'categoryasc') {
            return $fc = $fc->orderBy('category', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'categorydesc') {
            return $fc = $fc->orderBy('category', 'desc');
        }

        // 根据分类排列顺序排序
        if (!empty($request->order) && $request->order == 'orderselfasc') {
            return $fc = $fc->orderBy('order_self', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'orderselfdesc') {
            return $fc = $fc->orderBy('order_self', 'desc');
        }

        // 根据分类是否显示排序
        if (!empty($request->order) && $request->order == 'isshowasc') {
            return $fc = $fc->orderBy('is_show', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'isshowdesc') {
            return $fc = $fc->orderBy('is_show', 'desc');
        }

        // 根据自定义排序定义排序
        if (!empty($request->order) && $request->order == 'orderSelfasc') {
            return $fc = $fc->orderBy('order_self', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'orderSelfdesc') {
            return $fc = $fc->orderBy('order_self', 'desc');
        }
        return $fc;

        // 根据是否显示排序
        if (!empty($request->order) && $request->order == 'isshowasc') {
            return $fc = $fc->orderBy('is_show', 'asc');
        }

        // 根据
        if (!empty($request->order) && $request->order == 'isshowdesc') {
            return $fc = $fc->orderBy('is_show', 'desc');
        }
        return $fc;
    }
}
