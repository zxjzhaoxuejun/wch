<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/10/18
 * Time: 15:35
 */
namespace app\admin\model;
use think\Model;

class MemberReg extends Model{
    protected $autoWriteTimestamp=true;

    public function getStatusAttr($val)
    {
        switch ($val){
            case 0:
                return '未认证';
                break;
            case 1:
                return '已认证';
                break;
            case 2:
                return '审核中';
                break;
            default:
                return '未认证';
                break;
        }
    }
}