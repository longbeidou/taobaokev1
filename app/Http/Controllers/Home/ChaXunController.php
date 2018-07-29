<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\TaoBaoke\TaoBaoKeAPI;
use App\Libraries\TaoBaoke\Top\request\TbkDgItemCouponGetRequest;
use App\Libraries\TaoBaoke\Top\request\TbkItemGetRequest;

use App\Model\YouHuiQuan;
use App\Model\Category;

class ChaXunController extends Controller
{
    /**
     * 显示查询商品的主页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageInfo['title']       = '淘宝天猫优惠券查询';                                                 // 网页的title
        $pageInfo['description'] = '本网站提供最新的淘宝天猫优惠券的商品，将此网站先给自己深爱的女友！'; // 网站的description

        // 获取栏目的所有可以显示的信息
        $categoryAllInfo = Category::where('is_show', '是')
                                   ->where('form', '<>', '0')
                                   ->orderBy('order_self', 'asc')
                                   ->get()
                                   ->toArray();

        return view('home.ChaXun', ['pageInfo'=>$pageInfo,
                                    'categoryAllInfo'=>$categoryAllInfo
                                   ]);
    }

    /**
     * 显示查询结果的页面
     *
     * @return \Illuminate\Http\Response
     */
    public function result(Request $q)
    {
        if (empty($q->input('q')))
        {
            return redirect()->route('ChaXunIndex');
        } else {
            $pageInfo['title']       = '淘宝天猫优惠券查询';                                                 // 网页的title
            $pageInfo['description'] = '本网站提供最新的淘宝天猫优惠券的商品，将此网站先给自己深爱的女友！'; // 网站的description
            $qw = $q->input('q');

            // 获取栏目的所有可以显示的信息
            $categoryAllInfo = Category::where('is_show', '是')
                                       ->where('form', '<>', '0')
                                       ->orderBy('order_self', 'asc')
                                       ->get()
                                       ->toArray();

            //将字符串按照空格来分割成数组
            $qarr = explode(' ', $qw);
            $qarr = array_filter($qarr);
            foreach ($qarr as $key => $value) {
                $qarr[$key] = '%'.$value.'%';
            }
            $qarr = array_values($qarr);

            $yhq = new YouHuiQuan;
            foreach ($qarr as $key => $value) {
                $yhq = $yhq->where('goods_name','like',$value);
            }

            // 判断优惠券是否过期
            $date = date('Y-m-d');
            $dateTime = strtotime($date);
            $date = date('Y-m-d H:i:s', $dateTime);
            $yhq = $yhq->where('yhq_end', '>=', $date);
            unset($date);
            unset($dateTime);

            $results = $yhq->paginate(15);

            return view('home.ChaXunInfo',['q'=>$qw,
                                           'info'=>$results,
                                           'categoryAllInfo'=>$categoryAllInfo,
                                           'pageInfo'=>$pageInfo,
                                           ]);
        }        
    }    
}
