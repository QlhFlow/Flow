<?php
/**
 * 极客之家 高端PHP - 抽奖系统
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use think\Config;
use think\Db;
use think\Session;
use think\Request;
use app\admin\model\ProjectModel;
use app\admin\model\DrawModel;
class Draw extends Base
{
	/**
	 * 活动列表
	 */
	public function activity_list()
	{
		//查询所有项目对应奖品
		$project = new ProjectModel();
		$data = $project->DrawshowAll();
		$this->assign("data",$data);
		return $this->fetch();
	}

	/**
	 * 修改活动列表
	 * @return [type] [description]
	 */
	public function activity_update()
	{
		$draw = new DrawModel();

        if(request()->isAjax()){
            $param = input('post.');
            $id = $param['id'];
            unset($param['file']);//删除表中没有值
            unset($param['id']);//删除表中没有值
            $flag = $draw->activity_update($id,$param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $data = $draw->activity_updateshow($id);
        $this->assign("data",$data);//数据
        $this->assign("id",$id);//修改ID
        return $this->fetch();
	}

	/**
	 * 删除活动列表
	 * @return [type] [description]
	 */
	public function activity_delete()
	{
		$id = input('param.id');
        $draw = new DrawModel();
        $flag = $draw->activity_delete($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
	}

	/**
	 * 添加抽奖活动
	 * @return [type] [description]
	 */
	public function activity_add()
	{
		$draw = new DrawModel();
		if(input('param.'))
		{
		   $data = input("param.");
		   unset($data['file']);//删除表中没有值
           $flag = $draw->DrawAdd($data);
           return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        else
        {
	        return $this->fetch();
        }

	} 

	/**
	 * 查看活动详情 列表
	 * @return [type] [description]
	 */
	public function ActivityDetails()
	{
		$draw = new DrawModel();
		$lotid = input("param.id");
		$TableName = DB::name("draw")->where("draw_id",$lotid)->find();
		$PrizeDetail = $draw->ActivityDetails($TableName['drawtable_name']);
		$this->assign("data",$PrizeDetail);
		$this->assign("activityDetails",$TableName['details']);//奖品描述
		$this->assign("draw_name",$TableName['draw_name']);//奖品名称
		$this->assign("actionTable",$TableName['drawtable_name']);//奖品表名
		return $this->fetch("activity_details");
	}

	/**
	 * 活动详情  删除
	 */
	public function PrizeDel()
	{
		$id = input('param.id');
        $draw = new DrawModel();
        $flag = $draw->PrizeDel($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
	}

	/**
	 * 添加奖品
	 */
	public function add_lotter()
	{
		if(request()->isAjax()){
			$draw = new DrawModel();
            $param = input('post.');
           	$param['num'] = $param['sumnum'];
           	$table = $param['actionTable'];//表名
           	unset($param['actionTable']);//删除表中没有值
            $flag = $draw->add_lotter($param,$table);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $actionTable = input('param.actionTable');
        $draw_name = input('param.draw_name');
        $this->assign("actionTable",$actionTable);//奖品表名
        $this->assign("draw_name",$draw_name);//奖品名称
        return $this->fetch();
	}

	/**
	 * 修改奖品
	 */
	public function updatelotter_name()
	{
		$draw = new DrawModel();

        if(request()->isAjax()){

            $param = input('post.');
            $table = $param['table'];
            $id = $param['id'];
            unset($param['table']);//删除表中没有值
            unset($param['id']);//删除表中没有值
            $flag = $draw->updatelotter_name($id,$table,$param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $param = input('param.');
        $data = $draw->LotterFindname($param['id'],$param['actionTable']);
        $this->assign("id",$param['id']);//修改id
        $this->assign("table",$param['actionTable']);//表名
        $this->assign("data",$data);//数据
        return $this->fetch('updatelotter_name');
	}

	
}