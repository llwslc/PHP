<?php
namespace Admin\Controller;
use Think\Controller;
class HeaderYdController extends Controller {

    public function index(){

        $nullStr = "nullstring";
        session(preDate, $nullStr);
        session(nxtDate, $nullStr);
        session(zoneName, $nullStr);
        session(canalId, $nullStr);

        $this->display();
    }
}