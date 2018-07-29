<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Excel;
use App\Model\YouHuiQuan;

class ExcelController extends Controller
{
    /**
     * 显示上传Excel文件的界面
     *
     */
    public function create()
    {
        return view('admin.ExcelFileUpload');
    }

    /**
     * 处理上传的Excel文件，并进行入库保持
     */
    public function store(Request $request)
    {
        if ($request->input('name') == 'longbeidouexcel')
        {
            if ($request->hasFile('info'))
            {
                $destinationPath = storage_path().'/taobaoke/';
                $fileName = 'file.xls';
                $request->file('info')->move($destinationPath, $fileName);

                //将Excel中的内容导入数据库
                $filePath = $destinationPath.$fileName;
                $info = Excel::load($filePath)->get()->toArray();
                // $yhq = new YouHuiQuan;
                foreach ($info as $value) {
                    $yhq = new YouHuiQuan;

                    $yhq->goods_id = $value['1'];
                    $yhq->goods_name = $value['2'];
                    $yhq->image = $value['3'];
                    $yhq->info_link = $value['4'];
                    $yhq->cate = $value['5'];
                    $yhq->taobaoke_link = $value['6'];
                    $yhq->price = $value['7'];
                    $yhq->sales = $value['8'];
                    $yhq->rate = $value['9'];
                    $yhq->money = $value['10'];
                    $yhq->wangwang = $value['11'];
                    $yhq->ww_id = $value['12'];
                    $yhq->shop_name = $value['13'];
                    $yhq->flat = $value['14'];
                    $yhq->yhq_id = $value['15'];
                    $yhq->yhq_total = $value['16'];
                    $yhq->yhq_last = $value['17'];
                    $yhq->yhq_info = $value['18'];
                    $yhq->yhq_begin = $value['19'];
                    $yhq->yhq_end = $value['20'];
                    $yhq->yhq_link = $value['21'];
                    $yhq->yhq_goods = $value['22'];
                    
                    $yhq->save();
                }

                return redirect()->route('excelupload');
            } else {
                return redirect()->route('excelupload');
            }
        } else {
            return redirect()->route('excelupload');
        }
    }
}
