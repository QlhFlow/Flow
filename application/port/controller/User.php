<?php
/**
 * 极客之家 高端PHP - 用户添加数据管理
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\port\controller;
use think\Controller;
use think\Config;
use think\Db;
use think\Session;
use think\Loader;
use app\port\model\UserModel;
class User extends Controller
{

	/**
	 * 用户信息添加接口 
	 */
	public function UserAdd()
	{
		$data = input("param.");
		Loader::import('aes\aes', EXTEND_PATH);
		$key = "xingyuanauto.com";//加密秘钥
		$aes = new \aes\Aes($key);
		$str = $data['key'];//加密字符串
		$table = $aes->decrypt($str);//解密表名
		// $table = $aes->encrypt($str);//加密表名

		$project  = new UserModel;
		if(!isset($data) || empty($data)){
			exit(json_encode(array("start"=>0,"msg"=>'数据传入有误',"data"=>'')));
		}
			$data['car_series_id'] = implode(",", $data['car_series_id']);//车系车型
			$data['dealer_name'] = implode(",", $data['dealer_name']);//经销商
			$data['time'] = date("Y-m-d H:i:s");
			$data = array('name'=>'秦林慧','sex'=>'1',"dealer_name"=>'1,2',"email"=>'1502345454');
			$res = $project->ProjectAdd($data,$table);
		if($res)
		{
			exit(json_encode(array("start"=>1,"msg"=>'添加成功',"data"=>'')));
		}
		else
		{
			exit(json_encode(array("start"=>0,"msg"=>'添加失败',"data"=>'')));
		}
	}
}