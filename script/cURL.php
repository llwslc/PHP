<?php

/**
* post数据
*
*/
function curl_post($url, $post) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
        );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curl_post_xml($url, $post) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => array('Content-Type: text/xml; charset=utf-8'),
        CURLOPT_POSTFIELDS     => $post,
        );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
* get数据
*
*/
function curl_get($url, $get) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_BINARYTRANSFER => true,
        );

    $ch = curl_init( $url."?".$get);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


// test
$postStrTemp = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <request>
                    <userId>%s</userId>
                    <contentId>%s</contentId>
                    <consumeCode>%s</consumeCode>
                    <cpid>%s</cpid>
                    <hRet>%s</hRet>
                    <versionId>100</versionId>
                    <cpparam />
                    <packageID />
                </request>";
$postStrTempRes = sprintf($postStrTemp, $userId, $contentId, $consumeCode, $cpid, $hRet);

$data = curl_post_xml("http://service.pay.easou.com/sms/162/sbkISONotify.e", $postStrTempRes);
var_dump($data);

?>