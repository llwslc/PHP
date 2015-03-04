<?php

/*
* menu php test
* @param unknown
* @return unknown
*/

if (!function_exists('curl_init')){
	exit('您的PHP没有安装 配置cURL扩展.');
}

$AppId = "wxb7bb1a760bbef5bc";
$AppSecret = "1f624b1e4722fdc77f3321588c55a0f5";
$getAccessTokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".

$AppId."&secret=".$AppSecret;
$menuUrl = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=";
include "menu_code.php";

function getAccessToken($getAccessTokenUrl)
{
	$Curl = curl_init();
	curl_setopt($Curl, CURLOPT_URL, $getAccessTokenUrl);
	curl_setopt($Curl, CURLOPT_HEADER, false);
	curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($Curl, CURLOPT_SSL_VERIFYHOST, false);

	$Res = curl_exec($Curl);
	$Err = curl_error($Curl);

	if (false === $Res || !empty($Err))
	{
		$Errno = curl_errno($Curl);
		$Info = curl_getinfo($Curl);

		$result = "error";
	}
	else
	{
		$obj = json_decode($Res);
		$result = $obj -> access_token;
	}
	curl_close($Curl);

	return $result;
}

function postMenu($postMenuUrl, $jsonData)
{
	$Curl = curl_init();
	curl_setopt($Curl, CURLOPT_URL, $postMenuUrl);
	curl_setopt($Curl, CURLOPT_POST, 1);
	curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($Curl, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($Curl, CURLOPT_HEADER, false);
	curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($Curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($Curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($Curl, CURLOPT_TIMEOUT, 30);

	$Res = curl_exec($Curl);
	$Err = curl_error($Curl);

	if (false === $Res || !empty($Err))
	{
		$Errno = curl_errno($Curl);
		$Info = curl_getinfo($Curl);

		$result = "error";
	}
	else
	{
		$obj = json_decode($Res);
		$result = $obj -> errmsg;
	}
	curl_close($Curl);

	return $result;
}

$accessToken = getAccessToken($getAccessTokenUrl);
$postResult = postMenu($menuUrl.$accessToken, $menuData);
echo $postResult;

?>