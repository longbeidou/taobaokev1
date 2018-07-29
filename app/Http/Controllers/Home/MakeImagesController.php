<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use QrCode;
use Image;

use App\Model\Coupon;
use App\Model\CouponCategory;
use App\Model\Category;
use App\Model\FavoritesCategory;
use App\Model\FavoritesItem;

class MakeImagesController extends Controller
{
    /**
     * 生成图片的网页
     * $form 表示数据表， $id表示数据表的id
     *
     */
    public function index($form, $id)
    {
        switch ($form) {
            case 'coupon':
                $info = $this->couponInfo($id);
                if ($info === 'no') {
                    return redirect('/');
                }

                // 生成图片的操作
                $qrcodesSrc = 'coupon/qrcodes';   // 二维码的保存路径
                $imgSrc     = 'coupon/img';       // 商品的推广图片保存地址
                $bgimgSrc   = 'tuiguang/bg.png';  // 背景图的存放路径
                $taobaoSrc  = 'coupon/taobao';    // 淘宝网商品图的保存路径
                $name       = 'id';               // 文件的名称，如果为$id,可以生成多个文件

                break;
            
            default:
                return redirect('/');
        }

        // 生成商品的图片
        $img = $this->makeImage($info, $qrcodesSrc, $imgSrc, $bgimgSrc, $taobaoSrc, $name);

        return $img->response();
    }

    /**
     * 获取优惠券对应id的信息
     *
     */
    public function couponInfo($id)
    {
        $form = new Coupon;

        // 查看对应的id信息是否存在
        if (!$form->where('id', $id)->count()) {
            return 'no';
        }

        // 获取id对应的信息
        $couponInfo = $form->where('id', $id)
                           ->first()
                           ->toArray();

        //对应信息
        $info['goodsName']   = $couponInfo['goods_name'];
        $info['priceOrigin'] = $couponInfo['price'];
        $info['priceNow']    = $couponInfo['price_now'];
        $info['img']         = $couponInfo['image'];
        $info['linkTKL']     = $couponInfo['yhq_goods'];
        $info['linkInfo']    = route('urlCouponInfo', ['id'=>$id]);
        $info['couponInfo']  = $couponInfo['yhq_info'];

        unset($couponInfo);

        return $info;
    }

    /**
     * 检测并生成目录
     *
     */
    public function mkdirSelf($path)
    {
        $dirArr = explode('/', $path);

        foreach ($dirArr as $key => $value) {
            // 拼接路径
            $p = $dirArr[0];
            for ($i=0; $i<$key; $i++) {
                $p .= '/'.$value;
            }

            // 判断并生成目录
            if (!file_exists(public_path($p))) {
                mkdir(public_path($p));
            }       
        }
    }

    /**
     * 生成商品的推广图片
     *
     */
    public function makeImage($info, $qrcodesSrc, $imgSrc, $bgimgSrc, $taobaoSrc, $name)
    {
        // 检测并生成目录
        $this->mkdirSelf($qrcodesSrc);  // 二维码的目录
        $this->mkdirSelf($imgSrc);      // 商品推广图片的路径
        $this->mkdirSelf($taobaoSrc);   // 淘宝商品图片的路径

        // 生成跳转到商品详情页信息的二维码
        $qrcodeImg = QrCode::format('png')
                           ->size(242)
                           ->margin(0)
                           ->color(0,0,0)
                           ->backgroundColor(255,255,255)
                           ->generate($info['linkInfo'], public_path($qrcodesSrc.'/'.$name.'.png'));
        unset($qrcodeImg);

        // 将淘宝的图片保存到服务器本地
        $taobaoImg = Image::make($info['img'])
                          ->resize(889, null, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)  
                                $constraint->aspectRatio();  
                            })
                          ->save(public_path($taobaoSrc.'/'.$name.'.png'));
        unset($taobaoImg);

        // 生成商品的推广图
        $text    = $info['goodsName'];
        $strLen  = mb_strLen($text, 'utf-8');
        $textArr = [];
        $step    = 15;

        // 处理字符串变成数组
        for ($i = 0; $i < $strLen; $i += $step) {
            $textArr[] = mb_substr($text, $i, $step, 'utf-8');
        }

        $img = Image::make(public_path($bgimgSrc))
                    ->insert(public_path($qrcodesSrc.'/'.$name.'.png'), 'bottom-right', 61, 66) // 插入二维码
                    ->insert(public_path($taobaoSrc.'/'.$name.'.png'), 'top');                  // 插入淘宝图

        // 将商品名称写入图片
        foreach ($textArr as $key => $value) {
            $img = $img->text($value, 95, 950+$key*33, function($font) {
                                                               $font->file(public_path('tuiguang/simhei.ttf'));
                                                               $font->size('30px');
                                                               $font->color(array(69, 69, 69, 1));
                                                               $font->align('left');
                                                               $font->valign('top');
                                                               $font->angle(0);
                                                            });
        }

        // 写入现价
        $img = $img->text($info['priceNow'], 369, 1165, function($font) {
                                                           $font->file(public_path('tuiguang/simhei.ttf'));
                                                           $font->size('60px');
                                                           $font->color(array(255, 79, 30, 1));
                                                           $font->align('left');
                                                           $font->valign('top');
                                                           $font->angle(0);
                                                        });

        // 写入原价
        $img = $img->text($info['priceOrigin'], 118, 1120, function($font) {
                                                           $font->file(public_path('tuiguang/simhei.ttf'));
                                                           $font->size('40px');
                                                           $font->color(array(149, 149, 149, 1));
                                                           $font->align('left');
                                                           $font->valign('top');
                                                           $font->angle(0);
                                                        });

        // 写入优惠券面额
        $img = $img->text($info['couponInfo'], 55, 1180, function($font) {
                                                           $font->file(public_path('tuiguang/simhei.ttf'));
                                                           $font->size('25px');
                                                           $font->color(array(255, 79, 30, 1));
                                                           $font->align('left');
                                                           $font->valign('top');
                                                           $font->angle(0);
                                                        });

        // 写入网站的水印
        $webName = config('website.name').'独家分享！';
        $website = '更多淘宝天猫优惠券见:'.config('website.domain');
        $img = $img->text($webName, 35, 1230, function($font) {
                                                           $font->file(public_path('tuiguang/simhei.ttf'));
                                                           $font->size('25px');
                                                           $font->color(array(69, 69, 69, 0.5));
                                                           $font->align('left');
                                                           $font->valign('top');
                                                           $font->angle(0);
                                                        });
        $img = $img->text($website, 335, 1230, function($font) {
                                                           $font->file(public_path('tuiguang/simhei.ttf'));
                                                           $font->size('25px');
                                                           $font->color(array(69, 69, 69, 0.5));
                                                           $font->align('left');
                                                           $font->valign('top');
                                                           $font->angle(0);
                                                        });

        $img = $img->save(public_path($imgSrc.'/'.$name.'.png'));

        // 删除二维码图片
        if (file_exists(public_path($qrcodesSrc.'/'.$name.'.png'))) {
            unlink(public_path($qrcodesSrc.'/'.$name.'.png'));
        }
        // 删除淘宝商品图片
        if (file_exists(public_path($taobaoSrc.'/'.$name.'.png'))) {
            unlink(public_path($taobaoSrc.'/'.$name.'.png'));
        }

        return $img;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
