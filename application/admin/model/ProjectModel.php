<?php
/**
 * 极客之家 高端PHP - 项目模块
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class ProjectModel extends Model
{

    protected $table = "flow_project";
    protected $name = "project";

    /**
     * 查询所有项目
     * @return [type] [description]
     */
    public function showAll()
    {
    	// $res = Db::name($this->name)->alias("p")->join('flow_city c','p.city=c.region_id')->order("id desc")->select();
    	// foreach ($res as $key => $val) {
    	// 	$city = Db::name("city")->where("region_id",'in',$val['city'])->select();
    	// 	foreach ($city as $kk => $vv) {
    	// 		$arr[] = $vv['region_name'];;
    	// 		$res[$key]['city'] = join(",",$arr);
    	// 	}
    	// }
    	return $res = Db::name($this->name)->order("id desc")->select();
    }

    /**
     * 添加项目
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function project_add($data)
    {
        try{
            $result = DB::name("project")->insert($data);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加项目成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        } 
    }

    /**
     * 查询项目对应活动
     */
    public function DrawshowAll()
    {
        return DB::name("project")->alias("p")->join("flow_draw d","p.draw_id=d.draw_id")->select();
    }

    /**
     * 项目修改 查询单条数据
     * @param [type] $id [description]
     */
    public function ProjectUpdateshow($id)
    {   
        return DB::name("project")->where("id",$id)->find();
    }

    /**
     * 项目修改
     * @return [type] [description]
     */
    public function project_update($data,$id)
    {
        try{
            $result = DB::name("project")->where("id",$id)->update($data);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '修改成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        } 
    }

     /**
     * 删除项目详情 列表
     * @param [type] $id [description]
     */
    public function project_delete($id)
    {
        try{
            Db::name('project')->where('id', $id)->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除项目成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除项目成功'];
        }
        catch( PDOException $e)
        {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}