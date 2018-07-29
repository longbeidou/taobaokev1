<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Excel;
use Input;
use App\Model\Coupon;

class CouponController extends Controller
{
    /**
     * 显示优惠券的列表信息
     */
    public function index(Request $request)
    {
        $pageNumber = 10; // 每页显示的商品数量
        if (!empty($request->pageNumber)) {
            $pageNumber = $request->pageNumber;
        }

        $oldRequest = $request->all();

        // 将请求的参数整理达成URL对应的参数串
        $urlParameter = '';
        foreach ($oldRequest as $key => $value) {
            if ( $key != 'couponorder') {
                $urlParameter .= $key.'='.$value.'&';
            }            
        }

        $yhq = new Coupon;

        $yhq = $this->listWhere($yhq, $request);
        $info = $yhq->paginate($pageNumber);

        return view('admin.coupon.CouponList', ['info'=>$info, 'oldRequest'=>$oldRequest, 'urlParameter'=>$urlParameter]);
    }

    /**
     * 优惠券商品分析
     */
    public function analysisIndex()
    {
        $cateArray           = []; // 记录一级类目
        $couponAnalysisArray = []; // 记录优惠券分析的数据

        // 获取一级栏目
        Coupon::select(['cate'])->chunk(100, function($couponInfo) use(&$cateArray) {
            foreach ($couponInfo as $key => $coupon) {
                $cate = $coupon->cate;
                if (!in_array($coupon->cate, $cateArray)) {
                    $cateArray[] = $coupon->cate;
                }
            }
        });

        // 统计分析的数据
        $total = Coupon::count(); // 商品的总数
        foreach ($cateArray as $key => $value) {
            $couponAnalysisArray[$key]['cate'] = $value;
            $couponAnalysisArray[$key]['goodsSum']    = Coupon::where('cate', $value)->count();                // 商品的种类数量
            $couponAnalysisArray[$key]['goodsRate']   = $couponAnalysisArray[$key]['goodsSum'] / $total * 100; // 品类商品数量的占比
            $couponAnalysisArray[$key]['minPriceNow'] = Coupon::where('cate', $value)->min('price_now'); // 商品在该品类的最低卖价
            $couponAnalysisArray[$key]['maxPriceNow'] = Coupon::where('cate', $value)->max('price_now'); // 商品在该品类的最高卖价
            $couponAnalysisArray[$key]['avgPriceNow'] = Coupon::where('cate', $value)->avg('price_now'); // 商品在该品类的平均卖价
            $couponAnalysisArray[$key]['minSales']    = Coupon::where('cate', $value)->min('sales');     // 商品在该品类的最低销量
            $couponAnalysisArray[$key]['maxsales']    = Coupon::where('cate', $value)->max('sales');     // 商品在该品类的最高销量
            $couponAnalysisArray[$key]['avgsales']    = Coupon::where('cate', $value)->avg('sales');     // 商品在该品类的平均销量
        }
        unset($total);

        // 将数组按照商品种类数量降序排列
        for ($i = 0; $i < count($couponAnalysisArray)-1; $i++) {
            for ($m = $i+1; $m < count($couponAnalysisArray)-1; $m++) {
                if ($couponAnalysisArray[$i]['goodsSum'] < $couponAnalysisArray[$m]['goodsSum']) {
                    $tmp = $couponAnalysisArray[$i];
                    $couponAnalysisArray[$i] = $couponAnalysisArray[$m];
                    $couponAnalysisArray[$m] = $tmp;
                    unset($tmp);
                }
            }

        }

        return view('admin.coupon.CouponAnalysis', ['couponAnalysis' => $couponAnalysisArray]);
    }

    /**
     * 删除特定的优惠券 
     *
     */
    public function delete(Request $request)
    {
        $id = $request->id; // 优惠券的id

        $yhq = new Coupon;
        $yhq = $yhq->where('id', '=', $id);
        $n = $yhq->delete();

        if ($n) {
            return back()->with('deleteStatus','success');
        } else {
            return back()->with('deleteStatus','failed');
        }
    }

    /**
     * 显示上传Excel文件的窗口
     *
     */
    public function excelupload()
    {
        return view('admin.coupon.Excelupload');
    }

    /**
     * 处理上传的Excel文件
     *
     */
    public function exceluploadaction(Request $request)
    {
        if ($request->hasFile('info'))
        {
            set_time_limit(0);
            
            // 检查上传文件的后缀
            $file = Input::file('info');
            $entension = $file -> getClientOriginalExtension();
            if ($entension != 'xls') {
                return redirect()->route('listexcelupload')->with('status', 'fileFormatDiff');
            }

            $destinationPath = storage_path().'/taobaoke/';
            $fileName = 'file.xls';
            $request->file('info')->move($destinationPath, $fileName);

            //读取Excel中的数据
            $filePath = $destinationPath.$fileName;
            $info = Excel::load($filePath)->get()->toArray();

            // 判断文件是否是存在22列
            if (count($info[0]) != 22) { // 不存在22列
                 return redirect()->route('listexcelupload')->with('status', 'file');
            }

            // 更新数据库
            $this->insertOrUpdateInfo($info);  

            unset($info);
            unset($file);
            
            // // 将Excel中的信息生成10个文件，每个文件1000个商品信息
            // $this->makeExcelFiles($info);

            // // 将10个Excel文件导入数据库
            // for ($i = 1; $i <= 10; $i++) {
            //     $filePath = $destinationPath.$i.'.xls';
            
            //     // 获取Excel文件中的信息
            //     $info = Excel::load($filePath)->get()->toArray();

            //     // 更新数据库
            //     $this->insertOrUpdateInfo($info);
            // }

            // unset($info);
            unset($filePath);

            return redirect()->route('listexcelupload')->with('status', 'success');
        } else {
            //没有上传文件，直接返回
            return redirect()->route('listexcelupload')->with('status', 'failed');
        }
    }

    /**
     *  将Excel的信息写入10个Excel文件中
     */
    public function makeExcelFiles($info) {
        for ($i = 1; $i <= 10; $i++) {
            Excel::create($i, function($excel) use(&$info) {
                $excel->sheet('Page1', function($sheet) use(&$info) {
                    $sheet->row(1, array(
                        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22
                    ));
                    $tmp = array_splice($info, 1000);
                    $sheet->rows($info);
                    $info = $tmp;
                    unset($tmp);
                });
            })->store('xls', storage_path('taobaoke'));
        }

        unset($info);
    }

    /**
     *  将Excel的信息写入数据库
     */
    public function insertOrUpdateInfo($info) {
            foreach ($info as $value) {
                // 更新优惠券数据库的操作
                if (Coupon::where('goods_id', $value['1'])->count()){
                    // 更新数据库信息
                    $this->couponUpdate($value); // 耗内存，如果内存大可以开启，可以实现没有相同商品的功能
                    // $this->couponInsert($value);
                } else {
                    // 将信息写入数据库
                    $this->couponInsert($value);
                }
            }
    }

    /**
     *  将信息写入数据库
     */
    public function couponInsert($value) {

                $yhq = new Coupon;

                $yhq->goods_id      = $value['1'];
                $yhq->goods_name    = $value['2'];
                $yhq->image         = $value['3'];
                $yhq->info_link     = $value['4'];
                $yhq->cate          = $value['5'];
                $yhq->taobaoke_link = $value['6'];
                $yhq->price         = $value['7'];
                $yhq->sales         = $value['8'];
                $yhq->rate          = $value['9'];
                $yhq->money         = $value['10'];
                $yhq->wangwang      = $value['11'];
                $yhq->ww_id         = $value['12'];
                $yhq->shop_name     = $value['13'];
                $yhq->flat          = $value['14'];
                $yhq->yhq_id        = $value['15'];
                $yhq->yhq_total     = $value['16'];
                $yhq->yhq_last      = $value['17'];
                $yhq->yhq_info      = $value['18'];
                $yhq->yhq_begin     = $value['19'];
                $yhq->yhq_end       = $value['20'];
                $yhq->yhq_link      = $value['21'];
                $yhq->yhq_goods     = $value['22'];

                // 处理优惠券面额
                $yhqme  = $value['18'];
                $c      = str_replace(array('满','元减','元'), array('','元无条件券',''), $yhqme);
                $c      = str_replace(array('减'), array('元无条件券'), $c);
                $yhqarr = explode('无条件券', $c);
                $arr1   = intval($yhqarr[1]);
                if ($arr1 == 0) { // 对XX元无条件优惠券
                    $yhq->price_now = $yhq->price - $yhqarr[0]; // 现价
                    $yhq->rate_sales = 1 - $yhq->price_now / $yhq->price; // 优惠额度
                } else { // 对满XX元减XX的处理
                    $arr0 = intval($yhqarr[0]);
                    if ($yhq->price >= $arr0) {
                        $yhq->price_now  = $yhq->price - $yhqarr[1]; // 现价
                        $yhq->rate_sales = 1 - $yhq->price_now / $yhq->price; // 优惠额度
                    } else {
                        $yhq->price_now  = $yhq->price - ($yhqarr[1]/2); // 现价
                        $yhq->rate_sales = 1 - $yhq->price_now / $yhq->price; // 优惠额度
                    }
                }
                
                $yhq->save();

                unset($yhq);
                unset($yhqme);
                unset($c);
                unset($yhqarr);
                unset($arr1);
                unset($arr0);
    }

    /**
     *  更新信息到数据库
     */
    public function couponUpdate($value) {

                $yhq = Coupon::where('goods_id', $value['1'])->first();

                $yhq->goods_id      = $value['1'];
                $yhq->goods_name    = $value['2'];
                $yhq->image         = $value['3'];
                $yhq->info_link     = $value['4'];
                $yhq->cate          = $value['5'];
                $yhq->taobaoke_link = $value['6'];
                $yhq->price         = $value['7'];
                $yhq->sales         = $value['8'];
                $yhq->rate          = $value['9'];
                $yhq->money         = $value['10'];
                $yhq->wangwang      = $value['11'];
                $yhq->ww_id         = $value['12'];
                $yhq->shop_name     = $value['13'];
                $yhq->flat          = $value['14'];
                $yhq->yhq_id        = $value['15'];
                $yhq->yhq_total     = $value['16'];
                $yhq->yhq_last      = $value['17'];
                $yhq->yhq_info      = $value['18'];
                $yhq->yhq_begin     = $value['19'];
                $yhq->yhq_end       = $value['20'];
                $yhq->yhq_link      = $value['21'];
                $yhq->yhq_goods     = $value['22'];

                // 处理优惠券面额
                $yhqme  = $value['18'];
                $c      = str_replace(array('满','元减','元'), array('','元无条件券',''), $yhqme);
                $c      = str_replace(array('减'), array('元无条件券'), $c);
                $yhqarr = explode('无条件券', $c);
                $arr1   = intval($yhqarr[1]);
                if ($arr1 == 0) { // 对XX元无条件优惠券
                    $yhq->price_now = $yhq->price - $yhqarr[0]; // 现价
                    $yhq->rate_sales = 1 - $yhq->price_now / $yhq->price; // 优惠额度
                } else { // 对满XX元减XX的处理
                    $arr0 = intval($yhqarr[0]);
                    if ($yhq->price >= $arr0) {
                        $yhq->price_now  = $yhq->price - $yhqarr[1]; // 现价
                        $yhq->rate_sales = 1 - $yhq->price_now / $yhq->price; // 优惠额度
                    } else {
                        $yhq->price_now  = $yhq->price - ($yhqarr[1]/2); // 现价
                        $yhq->rate_sales = 1 - $yhq->price_now / $yhq->price; // 优惠额度
                    }
                }
                
                $yhq->save();

                unset($yhq);
                unset($yhqme);
                unset($c);
                unset($yhqarr);
                unset($arr1);
                unset($arr0);
    }

    /**
     *  处理搜索条件
     */
    public function listWhere(Coupon $yhq, Request $request) {
        //处理查询条件
        if (!empty($request->priceOriginMin)) { // 原价
            $yhq = $yhq->where('price','>=',$request->priceOriginMin);
        }
        if (!empty($request->priceOriginMax)) { // 原价
            $yhq = $yhq->where('price','<=',$request->priceOriginMax);
        }
        if (!empty($request->priceNowMin)) { // 现价
            $yhq = $yhq->where('price_now','>=',$request->priceNowMin);
        }
        if (!empty($request->priceNowMax)) { // 现价
            $yhq = $yhq->where('price_now','<=',$request->priceNowMax);
        }
        if (!empty($request->moneyMin)) { // 佣金
            $yhq = $yhq->where('money','>=',$request->moneyMin);
        }
        if (!empty($request->moneyMax)) { // 佣金
            $yhq = $yhq->where('money','<=',$request->moneyMax);
        }
        if (!empty($request->rateSalesMin)) { // 优惠占比
            $yhq = $yhq->where('rate_sales','>=',$request->rateSalesMin);
        }
        if (!empty($request->rateSalesMax)) { // 优惠占比
            $yhq = $yhq->where('rate_sales','<=',$request->rateSalesMax);
        }
        if (!empty($request->salesMin)) { // 销量
            $yhq = $yhq->where('sales','>=',$request->salesMin);
        }
        if (!empty($request->salesMax)) { // 销量
            $yhq = $yhq->where('sales','<=',$request->salesMax);
        }
        if (!empty($request->flat)) { // 平台
            if ($request->flat == '1') { // 淘宝平台
                $yhq = $yhq->where('flat','like','%淘宝%');
            } else { // 天猫平台
                $yhq = $yhq->where('flat','like','%天猫%');
            }
        }
        if (!empty($request->isRecommend)) { // 是否被推荐
            if ($request->isRecommend == '1') { // 推荐
                $yhq = $yhq->where('is_recommend','like','%是%');
            } else { // 未推荐
                $yhq = $yhq->where('is_recommend','like','%否%');
            }
        }
        if (!empty($request->q)) { // 商品的查询词
            //将字符串按照空格来分割成数组
            $qarr = explode(' ', $request->q);
            $qarr = array_filter($qarr);
            foreach ($qarr as $key => $value) {
                $qarr[$key] = '%'.$value.'%';
            }
            $qarr = array_values($qarr);

            foreach ($qarr as $key => $value) {
                $yhq = $yhq->where('goods_name','like',$value);
            }
        }
        if (!empty($request->couponorder)) {
            switch ($request->couponorder) {
                case 'priceoriginup':
                    $yhq = $yhq->orderby('price','asc');
                    break;
                
                case 'priceorigindown':
                    $yhq = $yhq->orderby('price','desc');
                    break;
                
                case 'pricenowup':
                    $yhq = $yhq->orderby('price_now','asc');
                    break;
                
                case 'pricenowdown':
                    $yhq = $yhq->orderby('price_now','desc');
                    break;
                
                case 'moneyup':
                    $yhq = $yhq->orderby('money','asc');
                    break;
                
                case 'moneydown':
                    $yhq = $yhq->orderby('money','desc');
                    break;
                
                case 'salesup':
                    $yhq = $yhq->orderby('sales','asc');
                    break;
                
                case 'salesdown':
                    $yhq = $yhq->orderby('sales','desc');
                    break;
                
                case 'rateSalesUp':
                    $yhq = $yhq->orderby('rate_sales','asc');
                    break;
                
                case 'rateSalesDown':
                    $yhq = $yhq->orderby('rate_sales','desc');
                    break;
                
                default:
                    $yhq = $yhq;
                    break;
            }
        }

        return $yhq;
    }

    /**
     *  根据优惠券id来删除多个优惠券
     */
    public function deleteMany(Request $request) {
        $ids = $request->goodsIdArray; // 优惠券的id

        $yhq = new Coupon;
        $yhq = $yhq->whereIn('id', $ids);
        $n = $yhq->delete();

        if ($n) {
            return back()->with('deleteStatus','success');
        } else {
            return back()->with('deleteStatus','failed');
        }
    }


    /**
     *  按日期删除优惠券
     */
    public function deleteDate(Request $request) {
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

        $yhq = new Coupon;
        $yhq = $yhq->whereBetween('created_at', [$dateBegin, $dateEnd]);
        $n = $yhq->delete();

        if ($n) {
            return back()->with('status','delteDateSuccess');
        } else {
            return back()->with('status','delteDateFailed');
        }
    }

    /**
     *  删除所有优惠券
     */
    public function deleteAll(Request $request) {
        $confirmCode = $request->confirmCode; // 删除确认码

        // 判读删除确认码是否存在
        if (empty($confirmCode)) {
            return back()->with('status', 'confireCodeWrong');
        } elseif ($confirmCode != 'alldelte001') {
            return back()->with('status', 'confireCodeWrong');
        }

        $yhq = new Coupon;
        $n = $yhq->truncate();

        if ($n) {
            return back()->with('status','delteAllSuccess');
        } else {
            return back()->with('status','delteAllFaild');
        }
    }

    /**
     *  根据优惠券id来推荐单个优惠券
     */
    public function recommendOne(Request $request) {
        $id = $request->id; // 优惠券的id

        $yhq = new Coupon;
        $yhq = $yhq->where('id', '=', $id);
        $n = $yhq->update(['is_recommend'=>'是']);

        if ($n) {
            return back()->with('recommendStatus','success');
        } else {
            return back()->with('recommendStatus','failed');
        }
    }

    /**
     *  根据优惠券id来推荐多个优惠券
     */
    public function recommendMany(Request $request) {
        $ids = $request->goodsIdArray; // 优惠券的id

        $yhq = new Coupon;
        $yhq = $yhq->whereIn('id', $ids);
        $n = $yhq->update(['is_recommend'=>'是']);

        // 更新对应id信息的淘口令
        foreach ($ids as $id) {
            $c = new Coupon;
            $info = $c->where('id', $id)->first();
            $tkl = ['url'=>$info->yhq_goods, 
                    'text'=>"超值活动，惊喜多多，[".config('website.name')."]亲情推荐！"
                    ];
            $info->tao_kou_ling = getTaoKouLing($tkl);
            $taoKouLingStr = (string)$info->tao_kou_ling;
            unset($c);

            // 如果成功获取淘口令写入数据库
            if (strlen($taoKouLingStr) > 4) {
                $m = new Coupon;
                $number = $m->where('id', $id)->update(['tao_kou_ling'=>$info->tao_kou_ling]);
                unset($m);
                unset($number);
            }
        }

        if ($n) {
            return back()->with('recommendStatus','success');
        } else {
            return back()->with('recommendStatus','failed');
        }
    }

    /**
     *  根据优惠券id来取消多个优惠券的推荐
     */
    public function cancelRecommendMany(Request $request) {
        $ids = $request->goodsIdArray; // 优惠券的id

        $yhq = new Coupon;
        $yhq = $yhq->whereIn('id', $ids);
        $n = $yhq->update(['is_recommend'=>'否']);

        if ($n) {
            return back()->with('cancelRecommendStatus','success');
        } else {
            return back()->with('cancelRecommendStatus','failed');
        }
    }

    /**
     *  根据优惠券id来取消单个优惠券的推荐
     */
    public function cancelRecommendOne(Request $request) {
        $id = $request->id; // 优惠券的id

        $yhq = new Coupon;
        $yhq = $yhq->where('id', '=', $id);
        $n = $yhq->update(['is_recommend'=>'否']);

        if ($n) {
            return back()->with('cancelRecommendStatus','success');
        } else {
            return back()->with('cancelRecommendStatus','failed');
        }
    }
}
