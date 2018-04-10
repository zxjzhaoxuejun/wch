<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/12/6
 * Time: 11:08
 */

namespace app\admin\model;

use think\Model;

class Messages extends Model{
    protected $autoWriteTimestamp=true;

    protected $auto=['ip'];

    public function setIpAttr()
    {
        return request()->ip();
    }
}