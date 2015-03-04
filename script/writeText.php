<?php

function writeFile($mString)
{
	//无法创建file文件夹,需要保证file文件夹的存在
    $path = '.\file\log'.date('_Y_m_d',time()).".txt";
    $fp = fopen($path, 'ab');
    fwrite($fp, $mString);
    fclose($fp);
}


// test
$writeStr = "writeStr ";
$writeStr .= date('Y-m-d H:i:s',time());
$writeStr .= "---------------------";
$writeStr .= "\r\n";
writeFile($writeStr);

?>