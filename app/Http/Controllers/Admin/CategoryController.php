<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $oldRequest = $request->all();
        $pageSize = $request->pageSize;
        if (empty($pageSize)) {
            $pageSize = 10;
        }

        $category = new Category;
        $category = $category->orderBy('order_self', 'asc');
        $info = $category->paginate($pageSize);

        return view('admin.category.index', ['info'=>$info, 'oldRequest'=>$oldRequest]);
    }

    /**
     * 显示增加分类的页面
     *
     */
    public function add()
    {

        return view('admin.category.Add');
    }

    /**
     * 执行增加分类的操作
     *
     *
     */
    public function addAction(Request $request)
    {
        $info = $request->except('_token');

        $category = new Category;

        $category->name = $request->name;
        $category->adzone_id = $request->adzone_id;
        if (empty($request->order_self)) {
            $category->order_self = 0;
        } else {
            $category->order_self = $request->order_self;
        }
        if (empty($request->is_show)) {
            $category->is_show = '是';
        } else {
            $category->is_show = $request->is_show;
        }
        $category->form = $request->form;

        $n = $category->save();

        if ($n) {
            return redirect()->route('adminCategoryIndex')->with('statusSuccess', '增加栏目成功！');
        } else {
            return back()->withInput()->withErrors('添加失败，请检查后重新提交！');
        }
    }

    /**
     * 显示修改分类的页面
     *
     */
    public function update(Request $request)
    {
        if (empty($request->id)) {
            return redirect()->route('adminCategoryIndex');
        }
        $c = new Category;

        $categoryInfo = $c->where('id', $request->id)->first();

        return view('admin.category.Edit', ['categoryInfo'=>$categoryInfo]);
    }

    /**
     * 执行修改分类的操作
     *
     */
    public function updateAction(Request $request)
    {
        $info = $request->except('_token', 'id');

        $category = new Category;

        if (empty($request->order_self)) {
            $info['order_self'] = 0;
        } else {
            $info['order_self'] = $request->order_self;
        }

        if (empty($request->is_show)) {
            $info['is_show'] = '是';
        } else {
            $info['is_show'] = $request->is_show;
        }

        $info['form'] = $request->form;

        $category = $category->where('id', $request->id);
        $n = $category->update($info);

        if ($n) {
            return redirect()->route('adminCategoryIndex')->with('statusSuccess', '成功更新id为'.$request->id.'的分类');
        } else {
            return back()->withErrors('更新失败，请检查后重新提交！');
        }
    }

    /**
     * 根据ID批量修改栏目排序的操作
     *
     */
    public function updateOrderselfs(Request $request)
    {
        $info = $request->order_self;

        $n = 0;
        foreach ($info as $id => $order_self) {
            $category = new Category;
            $category = $category->where('id', $id);
            $n += $category->update(['order_self'=>$order_self]);
        }

        if ($n) {
            return back()->with('statusSuccess', '成功更新'.$n.'条信息数据！');
        } else {
            return back()->withErrors('更新失败，请检查后重新提交！');
        }
    }

    /**
     * 根据id删除列表
     *
     */
    public function deleteById(Request $request)
    {
        $id = $request->id;

        if (empty($id)) {
            return redirect()->route('adminCategoryIndex');
        }

        // 删除栏目的信息
        $category = new Category;
        $n = $category->where('id', $id)->delete();

        if ($n) {
            return redirect()->route('adminCategoryIndex')->with('statusSuccess', '成功删除id为'.$id.'的分类');
        } else {
            return redirect()->route('adminCategoryIndex')->withErrors('删除id为'.$id.'的分类信息失败');
        }
    }

    /**
     * 根据id的集合来删除列表
     *
     */
    public function deleteByIds(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids)) {
            return redirect()->route('adminCategoryIndex');
        }

        // 删除栏目的信息
        $category = new Category;
        $n = $category->whereIn('id', $ids)->delete();

        if ($n) {
            return redirect()->route('adminCategoryIndex')->with('statusSuccess', '成功删除'.$n.'条栏目信息');
        } else {
            return redirect()->route('adminCategoryIndex')->withErrors('删除栏目信息失败');
        }
    }
}
