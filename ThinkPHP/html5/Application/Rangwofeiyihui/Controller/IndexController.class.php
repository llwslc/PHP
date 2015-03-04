<?php
namespace rangwofeiyihui\Controller;
use Think\Controller;
class IndexController extends Controller {

    /*
    登录先给 RecUserIdOnYd.php 发送数据
    然后打开 IndexController.class.php 校验数据

    006050060001.死亡复活
    死亡后提示是否使用"死亡复活"道具.

    006050060002.彩云变色
    点击"彩云变色"按钮,屏幕彩云将变为统一颜色.

    006050060003.安全保障
    点击"安全保障"按钮,屏幕底部将有安全云保证人物不会落地.
    */

    function _initialize()
    {
        //存入调用时间
        ini_set('date.timezone','Asia/Shanghai');
    }

    public function index()
    {
        if(empty($_GET))
        {
        }
        else
        {
            $userId = $_GET['userId'];
            $key = $_GET['key'];
            $cpId = $_GET['cpId'];
            $cpServiceId = $_GET['cpServiceId'];
            $channelId = $_GET['channelId'];
            $p = $_GET['p'];

            $getStr = "登录时间 : ";
            $getStr .= date('Y-m-d H:i:s',time());
            $getStr .= "---------------------";
            $getStr .= "\r\n";
            $getStr .= $_SERVER["QUERY_STRING"];
            $getStr .= "\r\n";
            $this->writeFile($getStr, "u");

            $this->assign('userId', $userId);
            $this->assign('key', $key);
        }

        $this->display();
    }

    public function curl_post($url, $post)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $post,
            CURLOPT_HTTPHEADER     => array('Content-Type: text/xml; charset=utf-8'),
            );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function curl_get($url, $get)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            );

        if($get == "")
        {
            $ch = curl_init($url);
        }
        else
        {
            $ch = curl_init($url."?".$get);
        }
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function writeFile($mString, $u)
    {
        $path = '.\log\rangwofeiyihui\ulog\ydulog'.date('_Y_m_d',time()).".txt";
        if($u === "u")
        {
            $path = '.\log\rangwofeiyihui\ulog\ydulog'.date('_Y_m_d',time()).".txt";
        }
        if($u === "p")
        {
            $path = '.\log\rangwofeiyihui\plog\ydplog'.date('_Y_m_d',time()).".txt";
        }
        if($u === "r")
        {
            $path = '.\log\rangwofeiyihui\rlog\ydrlog'.date('_Y_m_d',time()).".txt";
        }
        $fp = fopen($path, 'ab');
        fwrite($fp, $mString);
        fclose($fp);
    }

    public function onShopUp()
    {
        //道具选择
        if(empty($_GET))
        {
        }
        else
        {
            $cpId = '710440';
            $cpServiceId = '644016020455';
            $consumeCode = '00605006000';
            $cpparam = "0000000000000000";
            $userId = $_GET['userId'];
            $num = $_GET['num'];
            $key = $_GET['key'];
            $string = '<?xml version="1.0" encoding="UTF-8"?>
                        <request>
                            <msgType>WebGameBuyToolReq</msgType>
                            <versionId>01</versionId>
                            <userId>%s</userId>
                            <cpId>%s</cpId>
                            <cpServiceId>%s</cpServiceId>
                            <consumeCode>%s</consumeCode>
                            <channelId>10558000</channelId>
                            <transIDO>%s</transIDO>
                            <cpparam>%s</cpparam>
                        </request>';
            $cpparam = "0000000000000000".$num;
            $cpparam = substr($cpparam, strlen($cpparam)-16, 16);
            $postStr = sprintf($string, $userId, $cpId, $cpServiceId, $consumeCode.$num, $key, $cpparam);
            $resData = $this->curl_post("http://wap.cmgame.com/portalone/WebGameBuyToolServlet", $postStr);

            $getStr = "计费时间 : ";
            $getStr .= date('Y-m-d H:i:s',time());
            $getStr .= "---------------------";
            $getStr .= "\r\n";
            $getStr .= "userId=".$userId."&key=".$key;
            $getStr .= "\r\n";
            $getStr .= $resData;
            $getStr .= "\r\n";
            $this->writeFile($getStr, "p");

            if (!empty($resData))
            {
                $resObj = simplexml_load_string($resData, 'SimpleXMLElement', LIBXML_NOCDATA);
                $hRet = $resObj->hRet;
                $status = $resObj->status;
                $confirmId = $resObj->confirmId;
                $picVCodeURL = $resObj->picVCodeURL;

                //0：成功
                //1：请继续图形验证码购买确认流程
                //2：请继续短信验证码购买确认流程
                //3：请继续图形智能问答购买确认流程
                //99:处理失败

                if(($hRet == "0")&&($status == "1800"))
                {
                    echo "".$hRet."|".$status."|".$userId."|".$key."|".$num."";
                }

                if(($hRet == "1")&&($status == "2800"))
                {
                    echo "".$hRet."|".$status."|".$userId."|".$key."|".$num."|".$confirmId."|".$picVCodeURL."";
                }

                if(($hRet == "2")&&($status == "2800"))
                {
                    echo "".$hRet."|".$status."|".$userId."|".$key."|".$num."|".$confirmId."";
                }

                if(($hRet == "3")&&($status == "2800"))
                {
                    echo "".$hRet."|".$status."|".$userId."|".$key."|".$num."|".$confirmId."|".$picVCodeURL."";
                }

                if($hRet == "99")
                {
                    echo "".$hRet."|".$status."|".$userId."|".$key."|".$num."";
                }
            }
        }
    }

    public function getShopVCodePic()
    {
        //请求验证
        if(empty($_GET))
        {
        }
        else
        {
            $hRet = $_GET['hRet'];
            $picVCodeURL = $_GET['picVCodeURL'];

            if($hRet == "0")
            {
                echo 0;
            }

            if($hRet == "1")
            {
            }

            if($hRet == "2")
            {
                echo 2;
            }

            if($hRet == "3")
            {
                $picData = $this->curl_get($picVCodeURL, "");
                echo $picData;
            }

            if($hRet == "99")
            {
                echo 99;
            }
        }
    }

    public function confirmShopVCode()
    {
        //验证码验证
        if(empty($_GET))
        {
        }
        else
        {
            $confirmId = $_GET['confirmId'];
            $picVCode = $_GET['picVCode'];
            $string = '<?xml version="1.0" encoding="UTF-8"?>
                        <request>
                            <msgType>WebGameBuyToolConfirmReq</msgType>
                            <versionId>01</versionId>
                            <confirmId>%s</confirmId>
                            <picVCode>%s</picVCode>
                        </request>';
            $postStr = sprintf($string, $confirmId, $picVCode);
            $resData = $this->curl_post("http://wap.cmgame.com/portalone/WebGameBuyToolServlet", $postStr);

            $getStr = "计费结果 : ";
            $getStr .= date('Y-m-d H:i:s',time());
            $getStr .= "---------------------";
            $getStr .= "\r\n";
            $getStr .= "confirmId=".$confirmId."&picVCode=".$picVCode;
            $getStr .= "\r\n";
            $getStr .= $resData;
            $getStr .= "\r\n";
            $this->writeFile($getStr, "r");

            if (!empty($resData))
            {
                $resObj = simplexml_load_string($resData, 'SimpleXMLElement', LIBXML_NOCDATA);
                $hRet = $resObj->hRet;
                $status = $resObj->status;
                $balance = $resObj->balance;
                $point = $resObj->point;
                $cpparam = $resObj->cpparam;

                //0：成功
                //99:处理失败

                if(($hRet == "0")&&($status == "1800"))
                {
                    echo "".$hRet."|".$status."|".$cpparam."";
                }

                if($hRet == "99")
                {
                    echo "".$hRet."|".$status."";
                }
            }
        }
    }
}