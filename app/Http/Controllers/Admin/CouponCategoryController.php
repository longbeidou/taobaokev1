<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\CouponCategory;
use App\Model\Coupon;

class CouponCategoryController extends Controller
{
    /**
     * 显示优惠券分类的列表
     *
     */
    public function index(Request $request)
    {
        $pageNumber = 15; // 每页显示的分类数量
        if (!empty($request->pageNumber)) {
            $pageNumber = $request->pageNumber;
        }

        $oldRequest = $request->all();

        $category = new CouponCategory;
        $category = $category->orderBy('order', 'asc');
        $info = $category->paginate($pageNumber);

        $infoArr = $info->toArray();

        if ($infoArr['total']) { // 存在自定义的分类
            foreach ($info as $cate) {
                $c = new Coupon;
                $c = self::couponWhere($c, $cate->self_where);
                $number = $c->count();
                $count[$cate->id] = $number;
            }
        } else { // 不存咋自定义的分类
            $count = null;
        }


        return view('admin.coupon.CouponCategoryList', ['info'=>$info, 'oldRequest'=>$oldRequest, 'count'=>$count]);
    }

    /**
     * 显示增加优惠券分类的页面
     *
     */
    public function add()
    {
        return view('admin.coupon.CouponCategoryAdd');
    }

    /**
     * 显示增加优惠券分类的页面
     *
     */
    public function edit(Request $request)
    {
        $id = $request->id;

        $cc = new CouponCategory;
        $cc = $cc->where('id',$id);
        $info = $cc->first();

        // 判断数据库是否有对应的分类信息
        if (empty($info)) {
            return redirect()->route('couponCategoryAdd')->withInput()->withErrors('没有查询到对应的分类信息，可以选择添加信息...');
        }

        $whereStrArr = explode('or', $info->self_where);
        foreach ($whereStrArr as $key => $value) {
            $whereStrArr[$key] = explode('and', $value);
            foreach ($whereStrArr[$key] as $k => $v) {
                $whereArr[$key][] = explode('like', $v);
            }
        }

        return view('admin.coupon.CouponCategoryEdit',['info'=>$info, 'whereArr'=>$whereArr]);
    }

    /**
     * 向数据库写入或编辑分类的信息
     *
     */
    public function addaction(Request $request)
    {
        $whereArr = $request->sub;      // 第一部分的提交数据
        $orWhereArr = $request->sub2;   // 第二部分的提交数据

        $whereStr = ''; // 第一部分数据的组合字符串，用于where
        $orWhereStr = '';  // 第二部分数据的组合字符串，用于orwhere

        // 把第一部分的数据组合成字符串
        foreach ($whereArr as $first) {
            if (!empty($first['q'])) {
                $whereStr .= $first['cate'].'like'.$first['q'].'and';
            }
        }

        if (empty($whereStr)) {
            return back()->withInput()->withErrors('第一组商品的关键词不能全为空'); 
        }

        $whereStr = rtrim($whereStr,'and'); 

        // 把第二部分的数据组合成字符串
        foreach ($orWhereArr as $second) {
            if (!empty($second['q'])) {
                $orWhereStr .= $second['cate'].'like'.$second['q'].'and';
            }
        }

        // 组合第一部分和第二部分的数据
        if (!empty($orWhereStr)) {
            $orWhereStr = rtrim($orWhereStr,'and'); 
            $selfWhere = $whereStr.'or'.$orWhereStr;
        } else {
            $selfWhere = $whereStr;
        }

        $cc = new CouponCategory;

        if (!empty($request->id)) { // 更新数据
            $cc = $cc->where('id',$request->id);
            $n = $cc->update(['category_name'=>$request->name,
                              'self_where'=>$selfWhere,
                              'order'=>$request->order,
                              'is_show'=>$request->isShow
                            ]);

            if ($n) { // 更新数据成功
                return redirect()->route('couponCategoryList')->with('status','editSuccess');
            } else {
                return redirect()->route('couponCategoryList')->withInput()->withErrors('更新信息失败');
            }
        } else { // 添加数据
            $cc->category_name = $request->name;
            $cc->self_where = $selfWhere;
            $cc->order = $request->order;
            $cc->is_show = $request->isShow;
            
            $n = $cc->save();

            if ($n) {
                return redirect()->route('couponCategoryEdit',['id'=>$cc->id])->with('status','addSuccess');
            } else {
                return redirect()->route('couponCategoryAdd')->withInput()->withErrors('添加分类信息失败,请从新操作！');
            }
        }
    }

    /**
     * 批量修改排序
     * 
     */
    public function changeOrder(Request $request)
    {
        $n = 0; // 用于标记修改的次数

        // 验证排序的值是否符合要求
        foreach ($request->order as $orderNew) {
            if ($orderNew < 0 || $orderNew > 99) {
                return back()->withInput()->withErrors('排序的值需要在0-99之间');
            }
        }

        foreach ($request->order as $id => $orderNew) {
            $cc = new CouponCategory;
            $cc = $cc->where('id', '=', $id);
            $n += $cc->update(['order'=>$orderNew]);
        }
        
        if ($n) {
            return back()->with('status', 'changeOrderSuccess');
        } else {
            return back()->withInput()->withErrors('更新排序失败，请刷新页面重试');
        }
    }

    /**
     * 删除优惠券信息
     * 
     */
    public function del(Request $request)
    {
        $id = $request->id;

        $cc = new CouponCategory;
        $cc = $cc->where('id', $id);
        $n = $cc->delete();

        if ($n) { // 成功删除信息
            return back()->with('status','delOneSuccess');
        } else {
            return back()->withInput()->withErrors('未成功删除分类，请刷新页面重试！');
        }
    }

    /**
     * 批量删除优惠券信息
     * 
     */
    public function delMany(Request $request)
    {
        $ids = $request->categoryIdArray;

        // 如果没有传递要删除的信息则返回
        if (!count($ids)) {
            return back()->withInput()->withErrors('请选择要删除的分类！');
        }
        
        $cc = new CouponCategory;
        $cc = $cc->whereIn('id', $ids);
        $n = $cc->delete();

        if ($n) { // 成功删除信息
            return back()->with('status','delManySuccess');
        } else {
            return back()->withInput()->withErrors('未成功删除分类，请刷新页面重试！');
        }
    }

    /**
     * 设置分类是否在前台显示
     *
     */
    public function isShow(Request $request)
    {
        $isShow = $request->isshow;
        $id = $request->id;

        if ($isShow == 'yes') { // 设置显示
            $cc = new CouponCategory;
            $cc = $cc->where('id', $id);
            $n = $cc->update(['is_show'=>'是']);

            if ($n) {
                return back()->with('status','isShowSuccess');
            } else {
                return back()->withInput()->withErrors('设置失败，请重新刷新页面重试！');
            }
        } elseif ($isShow == 'no') { // 取消显示
            $cc = new CouponCategory;
            $cc = $cc->where('id', $id);
            $n = $cc->update(['is_show'=>'否']);

            if ($n) {
                return back()->with('status','isShowSuccess');
            } else {
                return back()->withInput()->withErrors('设置失败，请重新刷新页面重试！');
            }
        } else {
            return redirect()->route('couponCategoryList');
        }
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
}
