<?php

namespace App\Http\Controllers\Taobao;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Libraries\Taobaoke\Api;
use App\Libraries\Taobaoke\top\request\TbkItemGetRequest;
use App\Libraries\Taobaoke\top\request\WirelessShareTpwdCreateRequest;
use App\Libraries\Taobaoke\top\request\TbkDgItemCouponGetRequest;
use App\Libraries\Taobaoke\top\domain\GenPwdIsvParamDto;

class TestController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $c;

    public function __construct(){
        $this->c = Api::api();
        echo $this->c->appkey;
        echo '<hr />';
        echo $this->c->secretKey;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $req = new TbkItemGetRequest;
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
        $req->setQ("女装");
        $req->setPageNo("1");
        $req->setPageSize("1");
        $resp = $this->c->execute($req);

        $req = new WirelessShareTpwdCreateRequest;
        $tpwd_param = new GenPwdIsvParamDto;
        $tpwd_param->ext="{\"xx\":\"xx\"}";
        $tpwd_param->logo="http://m.taobao.com/xxx.jpg";
        $tpwd_param->url="http://m.taobao.com";
        $tpwd_param->text="超值活动，惊喜活动多多";
        $tpwd_param->user_id="24234234234";
        $req->setTpwdParam(json_encode($tpwd_param));
        // $resp = $this->c->execute($req);



        $req = new TbkDgItemCouponGetRequest;
        $req->setAdzoneId("123");
        $req->setPlatform("1");
        // $req->setCat("16,18");
        $req->setPageSize("1");
        $req->setQ("女装");
        $req->setPageSize('1');
        $req->setPageNo("1");
        $resp = $this->c->execute($req);

$b = $this->xmlToArr($resp);
// dd($b);

var_dump($resp->tbk_item_get_response);
var_dump($resp->result);

echo '<hr />';
        foreach ($resp->result as $key => $value) {
            var_dump($key);
            var_dump($value);
            dd($value->title);
        }

dd($resp->title);


        echo '<br />';
// echo $resp->model;
// var_dump($resp->model);
$a = (array)$resp->model;
dd($a);
        dd($resp->model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



public function xmlToArr($xml, $root = true)
    {

        if(!$xml->children())
        {
            return (string)$xml;
        }
        $array = array();
        foreach($xml->children() as $element => $node)
        {
            $totalElement = count($xml->{$element});
            if(!isset($array[$element]))
            {
                $array[$element] = "";
            }
            // Has attributes
            if($attributes = $node->attributes())
            {
                $data = array('attributes' => array(), 'value' => (count($node) > 0) ? $this->xmlToArr($node, false) : (string)$node);
                foreach($attributes as $attr => $value)
                {
                    $data['attributes'][$attr] = (string)$value;
                }
                if($totalElement > 1)
                {
                    $array[$element][] = $data;
                }
                else
                {
                    $array[$element] = $data;
                }
                // Just a value
            }
            else
            {
                if($totalElement > 1)
                {
                    $array[$element][] = $this->xmlToArr($node, false);
                }
                else
                {
                    $array[$element] = $this->xmlToArr($node, false);
                }
            }
        }
        if($root)
        {
            return array($xml->getName() => $array);
        }
        else
        {
            return $array;
        }

    }

}
