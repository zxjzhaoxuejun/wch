<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/11/19
 * Time: 10:45
 * 网页制作大赛报名表
 */
namespace app\admin\model;
use think\Model;

class Apply extends Model{
    protected $autoWriteTimestamp=true;
    //    模型器修改性别输出格式
    public function getSexAttr($val)
    {
        switch ($val){
            case 1:
                return '男';
                break;
            case 0:
                return '女';
                break;
            default:
                return '未知';
                break;
        }
    }

    public function getCityAttr($val)
    {
        switch ($val){
            case 1:
                return '本市';
                break;
            case 2:
                return '市外省内';
                break;
            case 3:
                return '省外';
                break;
            case 4:
                return '境外';
                break;
            default:
                return '未知';
                break;
        }
    }

    public function getOrganizationAttr($val)
    {
        switch ($val){
            case 1:
                return '深圳市互联网创业创新服务会';
                break;
            case 2:
                return '腾讯众创空间';
                break;
            case 3:
                return '赛格众创空间';
                break;
            case 4:
                return '华南城电商产业园';
                break;
			case 5:
                return '第一商务控股（289数字半岛）';
                break;
			case 6:
                return '深圳朋年投资集团';
                break;
            case 7:
                return '蚂蚁帮产服集团';
                break;
			case 8:
                return '京东云.博实永道（深圳）联合创新中心';
                break;
			case 9:
                return '中科美城';
                break;
			case 10:
                return '深商企业公馆';
                break;
            case 11:
                return '其他';
                break;
            default:
                return '未知';
                break;
        }
    }

    public function getLevelAttr($val)
    {
        switch ($val){
            case 1:
                return '初级工';
                break;
            case 2:
                return '中级工';
                break;
            case 3:
                return '高级工';
                break;
            case 4:
                return '技师';
                break;
            case 5:
                return '高级技师';
                break;
            case 6:
                return '无等级';
                break;
            default:
                return '未知';
                break;
        }
    }

    public function getEducationAttr($val)
    {
        switch ($val){
            case 1:
                return '小学';
                break;
            case 2:
                return '初中';
                break;
            case 3:
                return '高中';
                break;
            case 4:
                return '职高';
                break;
            case 5:
                return '中专';
                break;
            case 6:
                return '中技';
                break;
            case 7:
                return '高技';
                break;
            case 8:
                return '大专';
                break;
            case 9:
                return '本科';
                break;
            case 10:
                return '硕士';
                break;
            case 11:
                return '博士';
                break;
            default:
                return '未知';
                break;
        }
    }
}