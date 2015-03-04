<?php

$fileName = "test2.txt";
$filePath = "./files/".$fileName;
$fileUrl = "http://192.168.0.138/test/files/".$fileName;
 
/**
 * 配置文件操作(查询了与修改)
 * 默认没有第三个参数时，按照字符串读取提取''中或""中的内容
 * 如果有第三个参数时为int时按照数字int处理。
 * 调用demo
 * $name="admin";//kkkk
 * $bb='234';
 *
 * $bb=getConfig("./2.php", "bb", "string");
 * updateConfig("./2.php", "name", "admin");
*/
function getConfig($file, $key, $type="string")
{
    if(!file_exists($file))
    {
        return false;
    }
    $str = file_get_contents($file);
    if ($type == "int")
    {
        $config = preg_match("/".preg_quote($key)." = (.*);/", $str, $res);
        return $res[1];
    }
    else
    {
        $config = preg_match("/".preg_quote($key)." = \"(.*)\";/", $str, $res);
        if($res[1] == null)
        {  
            $config = preg_match("/".preg_quote($key)." = '(.*)';/", $str, $res);
        }
        return $res[1];
    }
}

function updateConfig($file, $key, $value, $type="string")
{
    if(!file_exists($file))
    {
        return false;
    }

    $str = file_get_contents($file);
    $valueNew = "";
    if($type == "int")
    {   
        $valueNew = preg_replace("/".preg_quote($key)." = (.*);/", $key." = ".$value.";", $str);
    }
    else
    {
        $valueNew = preg_replace("/".preg_quote($key)." = (.*);/", $key." = \"".$value."\";", $str);
    }
    file_put_contents($file, $valueNew);
    return true;
}

$cFileName = getConfig("./2.php", "fileName", "string");
$fileMD5 = getConfig("./2.php", "fileName", "string");
if($cFileName != $fileName)
{
	$fileMD5 = md5_file($filePath);
	updateConfig("./config.php", "fileName", $fileName);
	updateConfig("./config.php", "fileMD5", $fileMD5);
}

$res = array('fileUrl'=>$fileUrl, 'fileMD5'=>$fileMD5);
echo json_encode($res);

?>