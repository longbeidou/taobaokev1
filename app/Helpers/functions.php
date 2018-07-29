<?php

// namespace App\Helpers;

use App\Libraries\Taobaoke\Api;
use App\Libraries\Taobaoke\top\request\WirelessShareTpwdCreateRequest;
use App\Libraries\Taobaoke\top\domain\GenPwdIsvParamDto;

/*
 *  导出淘口令的函数
 *  参数说明，参数为一个数组，具体格式：$info = ['url'=>'','text'='','logo'=>'','ext1'=>'','ext2'=>'']
 */
function getTaoKouLing(array $info)
{
	$c = Api::api();
	$req = new WirelessShareTpwdCreateRequest;
	$tpwd_param = new GenPwdIsvParamDto;

	if (!empty($info['ext1']) && !empty($info['ext2'])) {
		$tpwd_param->ext="{\"".$info['ext1']."\":\"".$info['ext2']."\"}";
	}	

	if (!empty($info['logo'])) {
		$tpwd_param->logo=$info['logo'];
	}

	$tpwd_param->url=$info['url'];
	$tpwd_param->text=$info['text'];
	$tpwd_param->user_id=config('taobao.id');
	$req->setTpwdParam(json_encode($tpwd_param));
	$resp = $c->execute($req);

    return $resp->model;
}

/*
 *  把淘宝api返回的数据变成数组
 *  
 */
function simpleXMLElementToArray($resp) {
	$jsonStr = json_encode($resp);
	$jsonArray = json_decode($jsonStr,true);

	return $jsonArray;
}