<?php

define("SQLDEBUGFLAG", false);

class sqlsrv
{
    function connectSqlsrv()
    {
        $uid = "sa";
        $pwd = "sa";
        $serverName = "192.168.0.1,1433";
        $connectionInfo = array("UID" => $uid, "PWD" => $pwd, "Database"=>"master");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        $result = false;
        $debugFlag = SQLDEBUGFLAG;

        if($conn == false)
        {
            if($debugFlag)
            {
                echo "Connection could not be established.<br>";
                die(print_r(sqlsrv_errors(), true));
            }
            $result = false;
        }
        else
        {
            if($debugFlag)
            {
                echo "Connection established.<br>";
            }
            $result = true;
        }
        return $conn;
    }

    function insertSqlsrv($strSql)
    {
        $conn = $this->connectSqlsrv();
        $result = false;
        $debugFlag = SQLDEBUGFLAG;

        $string = iconv('utf-8', 'GB2312//IGNORE', $strSql);
        if(sqlsrv_query($conn, $string))
        {
            if($debugFlag)
            {
                echo "Statement executed.\n";
            }
            $result = true;
        }
        else
        {
            if($debugFlag)
            {
                echo "Error in statement execution.\n";
                die(print_r(sqlsrv_errors(), true));
            }
            $result = false;
        }

        sqlsrv_close($conn);

        return $result;
    }

    function selectSqlsrv($strSql)
    {
        $conn = $this->connectSqlsrv();
        $result = "false";
        $debugFlag = SQLDEBUGFLAG;

        $string = iconv('utf-8', 'GB2312//IGNORE', $strSql);
        $query = sqlsrv_query($conn, $string);
        if($query)
        {
            while($row = sqlsrv_fetch_array($query))
            {
                $result = $row[0];
            }
            if($debugFlag)
            {
                echo "Statement executed.\n".$result;
            }
        }
        else
        {
            if($debugFlag)
            {
                echo "Error in statement execution.\n";
                die(print_r(sqlsrv_errors(), true));
            }
            $result = "false";
        }

        sqlsrv_close($conn);

        return $result;
    }

    function updateSqlsrv($strSql)
    {
        $conn = $this->connectSqlsrv();
        $result = false;
        $debugFlag = SQLDEBUGFLAG;

        $string = iconv('utf-8', 'GB2312//IGNORE', $strSql);
        if(sqlsrv_query($conn, $string))
        {
            if($debugFlag)
            {
                echo "Statement executed.\n";
            }
            $result = true;
        }
        else
        {
            if($debugFlag)
            {
                echo "Error in statement execution.\n";
                die(print_r(sqlsrv_errors(), true));
            }
            $result = false;
        }

        sqlsrv_close($conn);

        return $result;
    }

    function execSqlsrv($strSql)
    {
		// 执行存储过程
        $conn = $this->connectSqlsrv();
        $result = false;
        $debugFlag = SQLDEBUGFLAG;

        $string = iconv('utf-8', 'GB2312//IGNORE', $strSql);
        $query = sqlsrv_query($conn, $string);

        if($debugFlag)
        {
            echo $strSql."<br>".$string."<br>";
        }
        if($query === false)
        {
            if($debugFlag)
            {
                echo "Error in statement execution.\n";
                die(print_r(sqlsrv_errors(), true));
            }
            $result = false;
        }
        else
        {
            $next_result = sqlsrv_next_result($query);
            if($next_result)
            {
                if($debugFlag)
                {
                    echo "Statement executed.\n";
                }
                while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
                {
					//返回结果需要有 ResNum 字段
                    $result = $row['ResNum'];
                }
            }
            else if(is_null($next_result))
            {
                if($debugFlag)
                {
                    echo "No more results.\n";
                }
                $result = false;
            }
            else
            {
                if($debugFlag)
                {
                    echo "Error in moving to next result.\n";
                    die(print_r(sqlsrv_errors(), true));
                }
                $result = false;
            }
        }

        sqlsrv_close($conn);

        return $result;
    }
}

?>
