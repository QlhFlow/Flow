<?php
/**
 * 极客之家 高端PHP - 东标-永乐票务 用户注册抽奖Model
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\port\model;
use think\Model;
use think\Db;
class UserlotterdbyongleModel extends Model
{
    protected $table = 'user_db_yongle';

    /**
     * 添加用户入库 进行抽奖活动
     * 东标项目 中游
     * @param [type] $data [description]
     */
    public function UserAdd($data)
    {
    	//先添加入库操作
    	$dataArr = array(
    			'name'=>$data['username'],
    			'phone'=>$data['numberphone'],
    			'dealer_name'=>$data['dealer'],
    			'models'=>$data['model'],
    			'time'=>time(),
    			'source'=>$data['source'],//来源状态
    		);
    	DB::name($this->table)->insert($dataArr);
    	$userId = Db::name($this->table)->getLastInsID();

    	//判断抽奖活动是否结束
		$endtime = strtotime("2017-04-30 23:59:59");//活动结束时间
		$begintime = time();//当前时间
		if($begintime > $endtime)
		{
			return array("start"=>2007,"msg"=>"数据保存成功，但活动已经结束");
		}
		
    	if($userId > 1)
    	{
    		//检测此用户有无抽取过奖项
			$havecj = $this->CkHavecj($userId);
			// print_r($havecj);die;
			if(!empty($havecj)){
				return array("start"=>2002,"msg"=>"您已经抽过奖");
			}

    		//开始抽奖
    		$res = $this->EndUserLotter($userId);
    		if($res)
    		{
    			return $res;
    		}
    		else
    		{
    			return array("start"=>2001,"msg"=>"抽奖失败");
    		}
    	}
    	else
    	{
    		return array("start"=>2008,"msg"=>"注册失败，请重试");
    	}
    }


    /**
     * 开始抽奖
     * @param [type] $userId [用户注册id]
     */
    public function EndUserLotter($userId)
    {
    	//检测确认是否插入成功，并获取用户手机号码
		$phone = $this->Ckgetphone($userId);

		//查询奖品 概率 数量
    	$LotterData = DB::name("lottery_db_yongle")->select();
    	if($LotterData)
    	{
    		foreach($LotterData as $vall){
				//$vall为奖项信息
				$prize[$vall['id']] = $vall['chance'];  //概率 (概率相加需要为100)
				$prizeList[$vall['id']] = $vall['id']; //奖项id
				$endlot = $vall['id']; //获取最后一个奖项 无库存时使用
			} 
			$times = 10;
			$max = 0;
			foreach ($prize as $k => $v)
			{
				$max = $v * $times + $max;
				$row['v'] = $max;
				$row['k'] = $k;
				$prizeZone[] = $row;
			} 
			$max--; //临界值
			$rand = mt_rand(0, $max);
			$zone = 4;
			foreach ($prizeZone as $k => $v)
			{
				if ($rand >= $v['v'])
				{
					if ($rand >= $prizeZone[$k + 1]['v'])
					{
						continue;
					}
					else
					{
						$zone = $prizeZone[$k + 1]['k'];
						break;
					}
				}
				$zone = $v['k'];
				break;
			}
			// return array("start"=>2222,"msg"=>$zone);die;
			//通过奖项id，获取此奖项库存
			$lotnum = $this->LotGoods($zone);
			$userinfo = array(
					'userid'=>$userId, //用户id
					'phone'=>$phone, //用户手机号
					'lotid'=>$zone //奖项id
				);
			//如果库存为0的话 给用户奖项为谢谢惠顾
			if($lotnum <= 0){  
				$userinfo['lotid'] = $endlot; //最后一个奖项
				$userinfo['status'] = 3; //无库存情况下插入  
				$userinfo['bflotid'] = $zone; //之前中奖无库存，奖项id
			}

			$jxname = $prizeList[$userinfo['lotid']];//用户获取奖项名称
			// return array("start"=>2222,"msg"=>$jxname);die;
		    //给 用户发奖 ->发奖成功，减库存 == 需要加入事务 回滚 == 
			$adduserlot = $this->SendLotUser($userinfo);
			if($adduserlot)
			{ 
				// 发奖成功后 执行 减库存
				$uplot = $this->UpdLotGoods($zone);

				if($uplot)
				{ 
					//发奖，减库存，执行成功
					return array("start"=>2004,"msg"=>$jxname);
				}
				else
				{
					 //减库存失败 发奖成功
					return array("start"=>2005,"msg"=>"注册成功,减库存失败");
				}  
			}
			else
			{
				// "失败"; //发奖失败
				return array("start"=>2006,"msg"=>"5");
			}
    	}
    }

    /**
     * 获取奖项库存
     * @param [type] $id [description]
     */
	public function LotGoods($id)
	{
		$end = Db::name('lottery_db_yongle')->field('num')->where('id',$id)->select();
		if(isset($end[0]['num']))
		{
			return $end[0]['num'];
		}
		else
		{
			return 0;
		}
	}

	/**
	 * 给用户发奖 存储记录
	 * @param [type] $data [description]
	 */
	public function SendLotUser($data)
	{
		return Db::name('lotuser_db_yongle')->insert($data);
	}

	/**
	 * 检测此用户有无抽过奖
	 * @param [type] $userid [description]
	 */
	public function CkHavecj($userId)
	{
		$phone = $this->Ckgetphone($userId);
		$res = Db::name('lotuser_db_yongle')->where('phone',$phone)->find();
		return $res['phone'];
	}

	/**
	 * 更新奖项库存 
	 * @param [type] $id [description]
	 */
	public function UpdLotGoods($id)
	{
		return Db::name('lottery_db_yongle')->where('id',$id)->setDec('num');
	}

	/**
	 * 通过userid，查询并获取手机号
	 * @param [type] $userid [description]
	 */
	public function Ckgetphone($userid)
	{
		$end = Db::name('user_db_yongle')->field('phone')->where('user_id',$userid)->select();
		if($end)
		{
			return $end[0]['phone'];
		}
		else
		{
			return null;
		}
	}
}