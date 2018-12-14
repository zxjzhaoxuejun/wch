<?php
/**
 * Created by PhpStorm.
 * User: 赵学军
 * Date: 2018-08-08
 * Time: 17:14
 */
namespace app\admin\model;

use think\Model;

class Enroll extends Model{
    protected  $autoWriteTimestamp=true;

    //模型器修改奖项输出格式
    public function getPrizeAttr($val)
    {
        switch ($val){
            case 1:
                return '科创之星企业奖TOP10';
                break;
            case 2:
                return '科创之星项目奖TOP10';
                break;
            default:
                return '科创之星人物奖TOP10';
                break;
        }
    }
}