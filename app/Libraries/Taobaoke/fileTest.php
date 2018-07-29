namespace App\Libraries\Taobaoke;

<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    $c = new TopClient;
    $c->appkey = '24567452';
    $c->secretKey = '59023132261bb01eec7555738091eca5';



$c = new TopClient;
$c->appkey = $appkey;
$c->secretKey = $secret;
$req = new TbkItemGetRequest;
$req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
$req->setQ("女装");
// $req->setCat("16,18");
// $req->setItemloc("杭州");
// $req->setSort("tk_rate_des");
// $req->setIsTmall("false");
// $req->setIsOverseas("false");
// $req->setStartPrice("10");
// $req->setEndPrice("10");
// $req->setStartTkRate("123");
// $req->setEndTkRate("123");
// $req->setPlatform("1");
$req->setPageNo("1");
$req->setPageSize("2");
$resp = $c->execute($req);

var_dump($resp);  
?>