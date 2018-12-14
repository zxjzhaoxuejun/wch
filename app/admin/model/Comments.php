<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/25
 * Time: 15:54
 */
namespace app\admin\model;
use think\Model;

class Comments extends Model{
    protected $autoWriteTimestamp=true;
    protected $createTime='createTime';
//    修改模型器获取时间数据
    public function getCreateTimeAttr($val)
    {
        return date('Y-m-d H:i:s',$val);
    }
}