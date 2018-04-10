<?php

namespace app\admin\model;

use think\Model;

class TbAdmin extends Model
{
    //
    protected $autoWriteTimestamp=true;//开启自动添加新增时间、修改时间

    protected $auto=['ip'];
//    格式化日期
    public function getLastTimeAttr($val)
    {
        return date('Y-m-d H:i:s',$val);
    }

//    模型器修改性别输出格式
    public function getSexAttr($val)
    {
        switch ($val){
            case 1:
                return '男';
                break;
            case 2:
                return '女';
                break;
            default:
                return '未知';
                break;
        }
    }

//    创建修改器，给密码MD5加密
    public function setPasswordAttr($val)
    {
        return md5($val);
    }

//    自动完成ip加入 
    public function setIpAttr()
    {
        return request()->ip();
    }

}
