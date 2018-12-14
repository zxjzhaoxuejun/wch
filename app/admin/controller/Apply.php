<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/11/19
 * Time: 11:09
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Apply as ApplyModel;

class Apply extends Base{
    public function index()
    {
        $data=ApplyModel::all();
        $this->assign('data',$data);
        return $this->fetch('list');
    }
    //导出xls
    public function derives(){
        //1.从数据库中取出数据
        $user=new ApplyModel();

        $list = $user->select();

        //2.加载PHPExcle类库
        vendor('PHPExcel.PHPExcel');
        //3.实例化PHPExcel类
        $objPHPExcel = new \PHPExcel();
        //4.激活当前的sheet表
        $objPHPExcel->setActiveSheetIndex(0);
        //5.设置表格头（即excel表格的第一行）
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '身份证')
            ->setCellValue('D1', '性别')
            ->setCellValue('E1', '手机号码')
            ->setCellValue('F1', '户籍所在地')
            ->setCellValue('G1', '竞赛项目工龄')
            ->setCellValue('H1', '社保卡电脑号')
            ->setCellValue('I1', '学历')
            ->setCellValue('J1', '现有职业资格等级')
            ->setCellValue('K1', '现有职业资格')
            ->setCellValue('L1', '单位名称')
            ->setCellValue('M1', '推荐机构')
            ->setCellValue('N1', '工作简历')
            ->setCellValue('O1', '个人照片');
        //设置A列水平居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置单元格宽度
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(40);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(5);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(30);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(30);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(30);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(30);
        //6.循环刚取出来的数组，将数据逐一添加到excel表格。
        for($i=0;$i<count($list);$i++){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2),$list[$i]['id']);//ID
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2),$list[$i]['name']);//名字
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($i+2),$list[$i]['identity']."\t");//身份证
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+2),$list[$i]['sex']);//性别
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($i+2),$list[$i]['tel']."\t");//手机
            $objPHPExcel->getActiveSheet()->setCellValue('F'.($i+2),$list[$i]['city']);//户籍
            $objPHPExcel->getActiveSheet()->setCellValue('G'.($i+2),$list[$i]['seniority']);//竞赛工龄
            $objPHPExcel->getActiveSheet()->setCellValue('H'.($i+2),$list[$i]['card']."\t");//社保电脑号
            $objPHPExcel->getActiveSheet()->setCellValue('I'.($i+2),$list[$i]['education']);//学历
            $objPHPExcel->getActiveSheet()->setCellValue('J'.($i+2),$list[$i]['level']);//资格等级
            $objPHPExcel->getActiveSheet()->setCellValue('K'.($i+2),$list[$i]['job']);//现有职业资格
            $objPHPExcel->getActiveSheet()->setCellValue('L'.($i+2),$list[$i]['company']);//单位名称
            $objPHPExcel->getActiveSheet()->setCellValue('M'.($i+2),$list[$i]['organization']);//推荐机构
            $objPHPExcel->getActiveSheet()->setCellValue('N'.($i+2),$list[$i]['resume']);//工作简历
            $objPHPExcel->getActiveSheet()->setCellValue('O'.($i+2),$list[$i]['pic']);//个人照片
        }
        //7.设置保存的Excel表格名称
        $filename = '网页大赛报名表'.date('Ymd',time()).'.xls';
        //8.设置当前激活的sheet表格名称；
        $objPHPExcel->getActiveSheet()->setTitle('网页大赛报名表');
        //9.设置浏览器窗口下载表格
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        //生成excel文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //下载文件在浏览器窗口
        $objWriter->save('php://output');
        exit;
    }
}