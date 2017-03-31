<?php
/**
 * 极客之家 高端PHP - Excel导出数据 Model
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */

namespace app\admin\model;
use think\Db;
use think\Model;
class ExcelModel extends Model
{
	protected $name = 'table';

	/**
	 * 查询宝沃表数据
	 * @param  [type] $table 表名
	 * @return [type]       array
	 */
	public function user_baowo($table)
	{
		$lists = DB::name($table)->order("dealer_id asc")->select();
		foreach ($lists as $kk => $vv) {
        	$lists[$kk]['time'] = date("Y-m-d H:i:s",$vv['time']);
            //查询用户获得的奖品
            $lotterArray = DB::name("user_baowo")->alias("b")->join("flow_lotuser l","l.userid=b.dealer_id")->field("b.dealer_id,l.lotid,l.userid")->where("b.dealer_id",$vv['dealer_id'])->select();
            $lists[$kk]['lotter'] = 0;
            foreach ($lotterArray as $k => $v) {
                $res = DB::name("lottery")->where("id",$v['lotid'])->field("name,id")->select();
                foreach ($res as $kkk => $vvv) {
                    $lists[$kk]['lotter'] = $vvv['name'];
                }
            }
        }
        return $lists;
	}


	/**
	 * 查询东标表数据
	 * @param  [type] $table 表名
	 * @return [type]       array
	 */
	public function user_dongbiao($table,$lottery_table,$lotuser_table)
	{
		$lists = DB::name($table)->order("user_id asc")->select();
		//查询经销商
        foreach ($lists as $key => $val) {
            $array = Db::name("dealer_dongbiao")->where("dealer_id",'in',$val['dealer_name'])->select();
            $arr = array(); 
            foreach ($array as $kk => $vv) {
                $arr[] = $vv['dealer_name'];
            }
            $string = join(",",$arr);
            $lists[$key]['dealer'] = $string;
    		$lists[$key]['time'] = date("Y-m-d",$val['time']);

            //查询用户获得的奖品
            $lotterArray = DB::name($table)->alias("d")->join("flow_".$lotuser_table." l","l.userid=d.user_id")->field("d.user_id,l.lotid,l.userid")->where("d.user_id",$val['user_id'])->select();
            $lists[$key]['lotter'] = "未抽奖";
            foreach ($lotterArray as $k => $v) {
                $res = DB::name($lottery_table)->where("id",$v['lotid'])->field("name,id")->select();
                foreach ($res as $kkk => $vvv) {
                    $lists[$key]['lotter'] = $vvv['name'];
                }
            }
			
			//查询车型
			$ModelCar = DB::name("car_series")->where("car_id",$val['models'])->field("car_id,car_name")->find();
			$lists[$key]['models'] = $ModelCar['car_name'];
		}
        // print_r($lists);die;
        return $lists;
	}

}