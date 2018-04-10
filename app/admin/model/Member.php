<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/29
 * Time: 9:38
 */
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class Member extends  Model{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $autoWriteTimestamp=true;

    protected $auto=['m_password'];

//    修改模型器获取性别数据
    public function getMSexAttr($val)
    {
        switch ($val){
            case 0:
                return '女';
                break;
            case 1:
                return '男';
                break;
            default:
                return '保密';
                break;
        }
    }

    public function getDeleteTimeAttr($val)
    {
        return date('Y-m-d H:i:s',$val);
    }

    public function setMPasswordAttr($val)
    {
        return md5($val);
    }



}