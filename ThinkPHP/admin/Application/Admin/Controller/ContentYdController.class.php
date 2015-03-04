<?php
namespace Admin\Controller;
use Think\Controller;
class ContentYdController extends Controller {

    public function index(){

        include './dbConfig.php';

        $showFlag = 0;
        $this->assign('totalAmount', 0);

        if(empty($_GET)||!isset($_GET['canalId'])||!isset($_GET['preDate'])||!isset($_GET['nxtDate'])||!isset($_GET['zoneName']))
        {
            $showFlag = 1;
            $canalId = session('canalId');
            $preDate = session('preDate');
            $nxtDate = session('nxtDate');
            $zoneName = session('zoneName');
            if (($preDate=="nullstring") || ($nxtDate=="nullstring"))
            {
                $showFlag = 1;
            }
            else
            {
                if ($zoneName == "nullstring")
                {
                    $showFlag = 2;
                }
                else
                {
                    if ($canalId == "nullstring")
                    {
                        $showFlag = 3;
                    }
                    else
                    {
                        $showFlag = 999;
                    }
                }
            }
        }
        else
        {
            $showFlag = 999;

            $canalId = $_GET['canalId'];
            $preDate = $_GET['preDate'];
            $nxtDate = $_GET['nxtDate'];
            $zoneName = $_GET['zoneName'];
            session(canalId, $canalId);
            session(preDate, $preDate);
            session(nxtDate, $nxtDate);
            session(zoneName, $zoneName);


            $sTotal = 0;
            $sSuccess = 0;
            $totalAmount = 0;

        }

        if ($showFlag === 999)
        {
            if ($canalId == "canal1")
            {
                if ($zoneName == "game1")
                {
                    $Content = M('Tbl_ydrchng', '', $dbConfigBahun);
                    $sqlWhere = 'tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'IOS\''.
                    ' AND tbl_ydrchng.consumecode = \'006031234042\' ';
                    $sTotal = $Content->where($sqlWhere)->count();

                    $sqlWhere = 'tbl_ydrchng.hRet = 0'.
                    ' AND tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'IOS\''.
                    ' AND tbl_ydrchng.consumecode = \'006031234042\' ';
                    $sSuccess = $Content->where($sqlWhere)->count();

                    $totalAmount = $Content->field('SUM(tbl_ydvalue.Price) as totalAmount')
                    ->join('left join tbl_ydvalue on tbl_ydrchng.consumecode = tbl_ydvalue.consumecode')
                    ->where($sqlWhere)
                    ->select();
                }

                if ($zoneName == "game2")
                {
                    $Content = M('Tbl_ydrchng', '', $dbConfigZhongjihuanxiang);
                    $sqlWhere = 'tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'IOS\''.
                    ' AND tbl_ydrchng.consumecode = \'006035092040\' ';
                    $sTotal = $Content->where($sqlWhere)->count();

                    $sqlWhere = 'tbl_ydrchng.hRet = 0'.
                    ' AND tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'IOS\''.
                    ' AND tbl_ydrchng.consumecode = \'006035092040\' ';
                    $sSuccess = $Content->where($sqlWhere)->count();                    

                    $totalAmount = $Content->field('SUM(tbl_ydvalue.Price) as totalAmount')
                    ->join('left join tbl_ydvalue on tbl_ydrchng.consumecode = tbl_ydvalue.consumecode')
                    ->where($sqlWhere)
                    ->select();
                }
            }

            if ($canalId == "canal2")
            {
                if ($zoneName == "game1")
                {
                    $Content = M('Tbl_ydrchng', '', $dbConfigBahun);
                    $sqlWhere = 'SELECT COUNT(DISTINCT cpparam ) AS num FROM tbl_ydrchng WHERE ('.
                    'tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'ANDROID\''.
                    ' AND (tbl_ydrchng.consumecode = \'006033610043\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610042\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610038\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610035\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610032\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610029\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610023\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610017\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610009\' )'.
                    ')';
                    $sTotal = $Content->query($sqlWhere);
                    $sTotal = $sTotal[0]['num'];

                    //echo $Content->getLastSql();

                    $sqlWhere = 'SELECT COUNT(DISTINCT cpparam ) AS num FROM tbl_ydrchng WHERE ('.
                    'tbl_ydrchng.hRet = 0 AND tbl_ydrchng.status = 1800'.
                    ' AND tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'ANDROID\''.
                    ' AND (tbl_ydrchng.consumecode = \'006033610043\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610042\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610038\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610035\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610032\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610029\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610023\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610017\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610009\' )'.
                    ')';
                    $sSuccess = $Content->query($sqlWhere);
                    $sSuccess = $sSuccess[0]['num'];

                    $sqlWhere = 'SELECT SUM(tbl_ydvalue.Price) as totalAmount FROM '.
                    '(SELECT distinct cpparam ,consumecode FROM tbl_ydrchng WHERE ('.
                    'tbl_ydrchng.hRet = 0 AND tbl_ydrchng.status = 1800'.
                    ' AND tbl_ydrchng.CreateTime >= \''.$preDate.
                    '\' AND tbl_ydrchng.CreateTime <= \''.$nxtDate.
                    '\' AND tbl_ydrchng.phoneFlag = \'ANDROID\''.
                    ' AND (tbl_ydrchng.consumecode = \'006033610043\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610042\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610038\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610035\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610032\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610029\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610023\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610017\' '.
                    ' OR tbl_ydrchng.consumecode = \'006033610009\' )'.
                    ') )a'.
                    ' LEFT JOIN tbl_ydvalue on a.consumecode = tbl_ydvalue.consumecode';
                    $totalAmount = $Content->query($sqlWhere);

                    if(($sSuccess > 123) && ($totalAmount[0][totalAmount] > 5000))
                    {
                        //$totalAmount[0][totalAmount] = $totalAmount[0][totalAmount] - 5000;
                    }
                }
            }

            $list = array(
                        array(
                            'sTotal'        => $sTotal,
                            'sSuccess'      => $sSuccess,
                            'ROW_NUMBER'    => 1,
                            )
                    );

            $this->assign('totalAmount', $totalAmount[0][totalAmount]/100);
            $this->assign('money_list', $list);
        }

        $this->display();

        switch ($showFlag) {
            case 1:
            break;
            case 2:
            echo "<script type='text/javascript'>messageBoxInformation('未选择游戏');</script>";
            break;
            case 3:
            echo "<script type='text/javascript'>messageBoxInformation('未填写渠道');</script>";
            break;

            default:
            break;
        }
    }
}