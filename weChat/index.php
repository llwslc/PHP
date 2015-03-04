<?php
/*
    2013.11.6
    微信公众平台 weixindevelop@163.com
    url:weixindeveloplc.duapp.com
    TOKEN:weixindevelop
*/

define("TOKEN", "weixindevelop");
$wechatObj = new wechatCallbackapiTest();

if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature())
        {
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = file_get_contents('php://input');

        if (!empty($postStr))
        {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $MsgType = $postObj->MsgType;
            if ($MsgType === "text")
            {
                //文本
                $keyword = trim($postObj->Content);
            }
            else if ($MsgType === "event")
            {
                //菜单
                $keyword = trim($postObj->EventKey);
            }
            else if ($MsgType === "image")
            {
                //图片
                $keyword = trim($postObj->EventKey);
            }
            else if ($MsgType === "voice")
            {
                //语音
                $keyword = trim($postObj->EventKey);
            }
            else if ($MsgType === "video")
            {
                //视频
                $keyword = trim($postObj->EventKey);
            }
            else if ($MsgType === "location")
            {
                //地理位置
                $keyword = trim($postObj->EventKey);
            }
            else if ($MsgType === "link")
            {
                //链接
                $keyword = trim($postObj->EventKey);
            }
            else
            {
                //NULL
            }

            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if(!empty($keyword))
            {
                include dirname(__FILE__)."/keyword/keyword.php";
                $resultStr = keyword($textTpl, $fromUsername, $toUsername, $time, $keyword);
                echo $resultStr;
            }
            else{
                //首次关注
                $msgType = "text";
                $contentStr = "上士闻道,勤而行之;\n";
                $contentStr .= "中士闻道,若存若亡;\n";
                $contentStr .= "下士闻道,大笑之.不笑不足以为道.\n\n";
                $contentStr .= "感谢关注:\"闻道下士\".";
                $contentStr .= "\n\n";
                $contentStr .= "查询天气格式如:\"天气 北京\"";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }
            echo $resultStr;
        }
        else
        {
            echo "闻道下士微信公众平台<br/><br/>";

            $url = "pic/qrcode_for_gh_weixindevelop_430.jpg";
            echo '<img src="'.$url.'">';
            echo '<br>';
            echo '<embed src="http://www.clocklink.com/clocks/5010-green.swf?TimeZone=GMT0800&"  width="333" height="100" wmode="transparent" type="application/x-shockwave-flash">';
        }
    }
}

?>