<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/10
 * Time: 8:53
 */

namespace  app\admin\model;

use think\Model;

class Vote extends Model{
    protected $autoWriteTimestamp=true;
    protected $auto=['ip'];
//自动完成ip加入
    public function setIpAttr()
    {
        return request()->ip();
    }
}