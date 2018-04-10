<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/28
 * Time: 9:45
 */

namespace app\admin\model;

use think\Model;

class SystemLog extends Model{

    protected $auto=['ip'];
    protected $autoWriteTimestamp=true;

    public function setIpAttr()
    {
        return request()->ip();
    }
//    修改状态输出值
    public function getStatueAttr($val)
    {
        switch ($val){
            case 0:
                return '未读';
                break;
            case 1:
                return '已读';
                break;
        }
    }

}