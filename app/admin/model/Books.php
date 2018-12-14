<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/25
 * Time: 15:53
 */
namespace app\admin\model;

use think\Model;

class Books extends Model{
//    修改模型器获取评分数据
    public function getRateAttr($val)
    {
        return round($val,1);
    }
}