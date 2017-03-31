<?php
/**
 * 极客之家 高端PHP - 城市 经销商管理
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\port\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Session;
use think\Cache;
use app\port\model\CityModel;
class City extends Controller
{

	/**
	 * 查询城市一级菜单
	 * @return [type] [description]
	 */
	public function city_father()
	{
		$city = new CityModel();
		$state = $city->city_father();
		exit(json_encode(array("start"=>1,"data"=>$state)));
	}

	/**
	 * 查询子级城市数据
	 * @return [type] [description]
	 */
	public function city_sublevel()
	{
		$city = new CityModel();
		$region_id = input("param.city_id/d");
		if(!isset($region_id) || empty($region_id)){
			exit(json_encode(array("start"=>0,"msg"=>'数据传入有误',"data"=>'')));
		}
		$data = $city->city_sublevel($region_id);
		exit(json_encode(array("start"=>1,"msg"=>'',"data"=>$data)));
	}

	/**
	 * 查询城市经销商全部数据
	 */
	public function Dealer()
	{
		$city = new CityModel();
		$data = $city->DealerAll();
		$DealerData = json_encode($data);
		if(Cache::get('DealerData')){
			$GetDealerData = Cache::get('DealerData');
		}
		else
		{
			Cache::set('DealerData',$DealerData);
			$GetDealerData = Cache::get('DealerData');
		}
		exit($GetDealerData);
	}

	/**
	 * 查询所有城市
	 * 全部数据
	 */
	public function CityData()
	{
		$city = new CityModel();
		$data = $city->CityData();
		$CityData = json_encode($data);
		if(Cache::get('DealerData')){
			$GetDealerData = Cache::get('CityData');
		}
		else
		{
			Cache::set('CityData',$CityData);
			$GetDealerData = Cache::get('CityData');
		}
		exit($GetDealerData);
	}
}