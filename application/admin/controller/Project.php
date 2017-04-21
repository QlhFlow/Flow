<?php
/**
 * 极客之家 高端PHP - 项目管理
 *
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
class Project extends Base
{

    /**
     * 项目列表
     * @return [type] [description]
     */
    public function project_list()
    {
        $project  = new ProjectModel;
        $data = $project->showAll();
        $this->assign('data',$data);                                              
        return $this->fetch();
    }

    /**
     * 项目添加
     * @return [type] [description]
     */
    public function project_add()
    {
         $project  = new ProjectModel;
        // $data = DB::name("city")->where("parent_id",0)->select();
        // $this->assign("data",$data);
        if(request()->isAjax())
        {
            $str = input('param.');
            $beginPeriod = str_replace("-","/",$str['beginPeriod']);
            $endPeriod = str_replace("-","/",$str['endPeriod']);
            $data['period'] = $beginPeriod.'-'.$endPeriod;
            $data['time'] = date("Y-m-d H:i:s");
            $data['project_name'] = $str['project_name'];
            $data['database_name'] = $str['database_name'];
            $data['draw_id'] = $str['draw_id'];
            if($str['start'] == 'on'){$data['start'] = 0;}
            // print_r($data);die;
            $flag = $project->project_add($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
           
        }
            //查询活动
            $draw = new DrawModel();
            $data = $draw->ActivityAll();
            $this->assign("data",$data);
            return $this->fetch();
    }

    /**
     * 项目列表修改
     */
    public function project_update()
    {
        $project  = new ProjectModel;
        $draw = new DrawModel();
        if(request()->isAjax()){
            $str = input('param.');
            $beginPeriod = str_replace("-","/",$str['beginPeriod']);
            $endPeriod = str_replace("-","/",$str['endPeriod']);
            $data['period'] = $beginPeriod.'-'.$endPeriod;
            $data['time'] = date("Y-m-d H:i:s");
            $data['project_name'] = $str['project_name'];
            $data['database_name'] = $str['database_name'];
            $data['draw_id'] = $str['draw_id'];
            $id = $str['id'];
            if($str['start'] == 'on'){$data['start'] = 0;}
            $flag = $project->project_update($data,$id);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $data = $project->ProjectUpdateshow($id);
        $DrawData = $draw->ActivityAll($id);
        $this->assign("id",$id);
        $this->assign("data",$data);
        $this->assign("DrawData",$DrawData);
        return $this->fetch();
    }

    /**
     * 删除项目
     * @return [type] [description]
     */
    public function project_delete()
    {
        $id = input('param.id');
        $project  = new ProjectModel;
        $flag = $project->project_delete($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}