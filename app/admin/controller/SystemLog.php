<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/27
 * Time: 16:11
 */
namespace app\admin\controller;

use app\admin\common\Base;

class SystemLog extends Base{

    public function index()
    {
        return $this->fetch('system/system_log');
    }

}