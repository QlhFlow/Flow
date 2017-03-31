<?php
/**
 * 极客之家 高端PHP - Excel导出导入
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\CapitalModel;
use app\admin\model\ExcelModel;
// use think\PHPExcel\PHPExcel;
class Excel extends	Controller	
{				
    /**
     * 根据表名判断导出数据
     * @return [type] [description]
     */
	public function index()
    {
        // $Capital = new CapitalModel;
        $ExcelTable = new ExcelModel;
        $table = input("param.table");
        if($table == "user_baowo")
        {
            $header = array('编号','姓名','手机','城市','中奖信息','注册时间');
            $data = $ExcelTable->user_baowo($table);
            foreach ($data as $key => $value) 
            {
                $dataArr[] = array(
                    'dealer_id'=>$value['dealer_id'],
                    'name'=>$value['name'],
                    'phone'=>$value['phone'],
                    'city'=>$value['city'],
					'lotter'=>$value['lotter'],
                    'time'=>$value['time'],
                );
            }

            $this->writer($header,$dataArr);//导出 此导出表头最长为A-Z,如果需要更长，请自行更改
        }
        
        if($table == "user_dongbiao" || $table == "user_db_yongle" || $table == "user_db_meituan")
        {
            if($table == "user_dongbiao"){$lottery_table = "lottery_dongbiao";$lotuser_table = "lotuser_dongbiao";}
            if($table == "user_db_meituan"){$lottery_table = "lottery_db_meituan";$lotuser_table = "lotuser_db_meituan";}
            if($table == "user_db_yongle"){$lottery_table = "lottery_db_yongle";$lotuser_table = "lotuser_db_yongle";}
            $header = array('编号','姓名','手机','经销商','车系车型','获得奖品','注册时间');
            $data = $ExcelTable->user_dongbiao($table,$lottery_table,$lotuser_table);
            foreach ($data as $key => $value) 
            {
                $dataArr[] = array(
                    'user_id'=>$value['user_id'],
                    'name'=>$value['name'],
                    'phone'=>$value['phone'],
					'dealer'=>$value['dealer'],
					'models'=>$value['models'],
                    'dealer_name'=>$value['lotter'],
                    'time'=>$value['time']
                );
            }
            
            $this->writer($header,$dataArr);//导出 此导出表头最长为A-Z,如果需要更长，请自行更改
        }

        
        // $list = $this->reader('./UploadFiles/excel/ceshi.xls');//导入
        // dump($list);exit;
    }

    /**
     * 导出数据列表
     * @param  [type]  $header [description]
     * @param  [type]  $data   [description]
     * @param  boolean $name   [description]
     * @param  integer $type   [description]
     * @return [type]          [description]
     */
    static function writer($header, $data,$name=false,$type = 0) {
        //导出
        $result = import("PHPExcel",EXTEND_PATH.'PHPExcel');
        if(!$name){$name=date("Y-m-d-H-i-s",time());}
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        foreach($header as $v){
            $colum = chr($key);
            $objPHPExcel->getActiveSheet()->getColumnDimension($colum)->setWidth(15);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getRowDimension(1)->setRowHeight(20);
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value) {// 列写入
                $j = chr($span);
                $objActSheet->getRowDimension($column)->setRowHeight(20);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('chen.data');
        $objPHPExcel->setActiveSheetIndex(0);
        $fileName = iconv("utf-8", "gb2312", './Data/excel/'.date('Y-m-d_', time()).time().'.xls');
        $saveName = iconv("utf-8", "gb2312", $name.'.xls');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if ($type == 0) {
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"$saveName\"");
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        } else {
            $objWriter->save($fileName);
            return $fileName;
        }
    }

    /**
     * 导入数据
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    static function reader($file) {
        if (self::_getExt($file) == 'xls') {
            $result = import("Excel5",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } elseif (self::_getExt($file) == 'xlsx') {
            $result = import("Excel2007",EXTEND_PATH.'PHPExcel/PHPExcel/Reader');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } else {
            return '路径出错';
        }

        $PHPExcel     = $PHPReader->load($file);
        $currentSheet = $PHPExcel->getSheet(0);
        $allColumn    = $currentSheet->getHighestColumn();
        $allRow       = $currentSheet->getHighestRow();
        for($currentRow = 1; $currentRow <= $allRow; $currentRow++){
            for($currentColumn='A'; $currentColumn <= $allColumn; $currentColumn++){
                $address = $currentColumn.$currentRow;
                $arr[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        return $arr;
    }

    private static function _getExt($file) {
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    public function out(){
        $file_name   = "成绩单-".date("Y-m-d H:i:s",time());
        $file_suffix = "xlsx";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file_name.$file_suffix");

        //根据业务，自己进行模板赋值。
        return $this->fetch();
    }

    public function in(){
        $content = file_get_contents('./UploadFiles/excel/ceshi.xls');
        dump($content);exit;

    } 
}
