<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/12/6
 * Time: 9:46
 */

namespace app\admin\model;

use think\Model;

class Feedback extends Model{

    protected $auto=['f_ip'];
    protected $autoWriteTimestamp=true;

    public function setFIpAttr()
    {
        return request()->ip();
    }


}