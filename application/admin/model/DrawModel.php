<?php
/**
 * 极客之家 高端PHP - 用户留资列表
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */

namespace app\admin\model;
use think\Db;
use think\Model;
class DrawModel extends Model
{
    protected $table = '';


    /**
     * 添加活动
     * @param [type] $data [description]
     */
    public function DrawAdd($data)
    {
        try{
            $data['time'] = date("Y-m-d");
            if($data['start'] == 'on'){$data['start'] = 0;}
            $result = DB::name("draw")->insert($data);
            if(false === $result){               
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 修改活动列表  查询修改数据
     * @param  [type] $id [修改id]
     * @return [type]     [description]
     */
    public function activity_updateshow($id)
    {
        return DB::name("draw")->where("draw_id",$id)->find();
    }

    /**
     * 修改活动列表数据
     * @param  [type] $id    [修改ID]
     * @param  [type] $param [修改数据]
     * @return [type]        [description]
     */
    public function activity_update($id,$param)
    {
        try{
            if($param['start'] == 'on'){$param['start'] = 0;}
            $result = DB::name("draw")->where("draw_id",$id)->update($param);
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
     * 删除活动列表
     * @param  [type] $id [删除ID]
     * @return [type]     [description]
     */
    public function activity_delete($id)
    {
        try{
            Db::name('draw')->where('draw_id', $id)->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除留资用户成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除活动详情成功'];
        }
        catch( PDOException $e)
        {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 奖品详情
     */
    public function ActivityDetails($table)
    {
        return DB::name($table)->select();
    }

    /**
     * 添加活动奖品
     * @param [type] $data  添加数据
     * @param [type] $table 表名
     */
    public function add_lotter($data,$table)
    {
        try{
            if($data['status'] == 'on'){$data['status'] = 0;}
            $result = DB::name($table)->insert($data);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 查询对应单条奖品
     * @param [type] $id    奖品id
     * @param [type] $table 表名
     */
    public function LotterFindname($id,$table)
    {
        return DB::name($table)->where("id",$id)->find();
    }

    /**
     * 根据条件修改奖品
     * @param  [type] $id    [奖品id]
     * @param  [type] $table [表]
     * @param  [type] $param [修改数据]
     * @return [type]        [true]
     */
    public function updatelotter_name($id,$table,$param)
    {
      try{
            if($param['status'] == 'on'){$param['status'] = 0;}
            $result = DB::name($table)->where("id",$id)->update($param);
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
     * 删除活动详情 列表
     * @param [type] $id [description]
     */
    public function PrizeDel($id)
    {
        try{
            Db::name('lottery')->where('id', $id)->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除留资用户成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除活动详情成功'];
        }
        catch( PDOException $e)
        {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 查询活动列表
     * @return [type] [description]
     */
    public function ActivityAll()
    {
        return DB::name("draw")->field("draw_id,draw_name")->select();
    }
}