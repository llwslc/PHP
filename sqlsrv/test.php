<?php
// write by lichao
include "config.php";

//存入调用时间
ini_set('date.timezone','Asia/Shanghai');

$Res = pay_result_notify_process();

function pay_result_notify_process()
{
    $postStr = file_get_contents('php://input');

    if (!empty($postStr))
    {
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        $hRet = $postObj->hRet;
        $cpId = $postObj->cpid;
        $status = $postObj->status;
        $contentId = $postObj->contentId;
        $versionId = $postObj->versionId;
        $userId = $postObj->userId;
        $consumeCode = $postObj->consumeCode;
        $cpparam = $postObj->cpparam;

        if ($cpparam == "")
        {
            $cpparam = "0000000000000000";
        }
    }
    else
    {
        return "PARAM ERROR";
    }

    $textTpl = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <response>
                    <hRet>%s</hRet>
                    <message>%s</message>
                </response>";
    $contentStr = 'successful';

    if (1 == $hRet) {
        if (1800 == $status) {
            $contentStr = 'failure';
        }
    }

    $resultStr = sprintf($textTpl, $hRet, $contentStr);

    echo $resultStr;

    srand((double)microtime()*1000000);
    $rand_number = rand();
    $rand_number = $rand_number%100;

    // SQL
    $sqlObj = new sqlsrv();

    $regex = '#^[0-9]+a[0-9]a[0-9]+a[0]+$#';
    $matches = array();

    if(preg_match($regex, $cpparam, $matches))
    {
        $sql = "USE SKYGameUserDB EXEC SP_GP_YDPayCoinResult '".$hRet."'
                , '".$cpId."' , '".$status."' , '".$contentId."'
                , '".$versionId."' , '".$userId."' , '".$consumeCode."' , '".$cpparam."', 'IOS'";
    }
    else
    {
        $sql = "USE SKYGameUserDB EXEC SP_GP_YDPayCoinResultShua '".$hRet."'
                , '".$cpId."' , '".$status."' , '".$contentId."'
                , '".$versionId."' , '".$userId."' , '".$consumeCode."' , '".$cpparam."', 'IOS'";
    }

    $execRes = $sqlObj->execSqlsrv($sql);
    if ($execRes === false)
    {
        $string = $sql;
        $string .= "\r\n";
        $path = "ydlog".date('_Y_m_d',time()).".txt";
        $fp = fopen($path, 'ab');
        fwrite($fp, $string);
        fclose($fp);

        return "DATEBASE FAILURE ".strval($rand_number)." ".$data." ";
    }

    return "SUCCESS ".strval($rand_number)." ".$data." ";
}

?>
