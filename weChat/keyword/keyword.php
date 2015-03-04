<?php

/**
* keyword php test
* @param $textTpl $fromUsername $toUsername $time $keyword
* @return unknown
*/

function keyword($textTpl, $fromUsername, $toUsername, $time, $keyword)
{
    if ($keyword == "小风机器人")
    {
        $keyword = "闻道下士";
    }
    else if ($keyword == "闻道下士")
    {
        $keyword = "小风机器人";
    }
    else
    {
    }

    if($keyword == "?" || $keyword == "？")
    {
        $msgType = "text";
        $contentStr = date("Y-m-d H:i:s",time());
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    }
    else
    {
        if(strpos($keyword, "天气") !== false)
        {
            $temp = strtok($keyword, " ");
            $temp = strtok(" ");
            $flag = false;

            while ($temp !== false)
            {
                $flag = true;
                $weather = $temp;
                $temp = strtok(" ");
            }
            if ($flag === true)
            {
                include dirname(__FILE__)."/../weather/weather.php";
                $msgType = "text";
                $contentStr = getWeather($weather);
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }
            else
            {
                $msgType = "text";
                $contentStr = "天气查询格式为'天气 北京'.";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }
        }
        else
        {
            include dirname(__FILE__)."/../chat/chatRobot.php";
            $msgType = "text";
            $contentStr = getChat($keyword);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        }
    }

    return $resultStr;
}

?>