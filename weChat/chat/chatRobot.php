<?php

/**
* chat php test
* @param $keyword
* @return unknown
*/

function getChat($keyword)
{
    $url = "http://xiaofengrobot.sinaapp.com/api.php?text=".$keyword;

    $file = file_get_contents($url);

    $contentStr = trim($file);

    if(stripos($contentStr, "小风机器人"))
    {
        $m_contentStr = str_ireplace("小风机器人", "闻道下士", $contentStr);
    }
    else if(stripos($contentStr, "问题%答案"))
    {
        $m_contentStr = "嗯, 然后呢?";
    }
    else
    {
        $m_contentStr = $contentStr;
    }

    return $m_contentStr;
}

//汉字转换为16进制编码
function hexEncode($s)
{
    return preg_replace('/(.)/es',"str_pad(dechex(ord('\\1')),2,'0',STR_PAD_LEFT)",$s);
}
//16进制编码转换为汉字
function hexDecode($s)
{
    return preg_replace('/(\w{2})/e',"chr(hexdec('\\1'))",$s);
}

?>