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
class CapitalModel extends Model
{
    protected $table = '';
    
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * 根据表名查询对应用户数据
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    // public function user_databaseName($table)
    // {   
    //     //宝沃表
    //     if($table['database_name'] == "user_baowo")
    //     {
    //        return Db::name("user_baowo")->order("dealer_id desc")->select();
    //     }
    //     //东标表
    //     else if($table['database_name'] == "user_dongbiao")
    //     {
    //         $res = Db::name("user_dongbiao")->order("user_id desc")->select();
    //         foreach ($res as $key => $val) {
    //             $array = Db::name("user_baowo")->where("user_id",'in',$val['dealer_name'])->select();
    //             $arr = array(); 
    //             foreach ($array as $kk => $vv) {
    //                 $arr[] = $vv['dealer_name'];
    //             }
    //             $string = join(",",$arr);
    //             $res[$key]['dealer'] = $string;
    //         }
    //         return $res;
    //     }
    // }
    
    /**
     * 修改用户信息
     * 查询单条数据
     * 宝沃
     * @param [type] $id [description]
     */
    public function BuserWfind($id)
    {
        return DB::name("user_baowo")->where("dealer_id",$id)->find();
    }

    /**
     * 修改用户信息
     * 查询单条数据
     * 东标
     * @param [type] $id [description]
     * @param [type] $table [查询表]
     */
    public function DuserBfind($id,$table)
    {
        return DB::name($table)->where("user_id",$id)->find();
    }

    /**
     * 修改用户数据
     * 宝沃
     * @param [type] $data [description]
     */
    public function BuserWedit($data)
    {
        try{
            $result = DB::name("user_baowo")->where("dealer_id",$data['id'])->update(["name"=>$data['name'],"phone"=>$data['phone'],"city"=>$data['citys'],"time"=>time()]);
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
     * 修改用户数据
     * 东标项目
     * @param [type] $data  [新修改数据]
     * @param [type] $table [修改用户表]
     */
    public function DuserBedit($data,$table)
    {
        try{
            $result = DB::name($table)->where("user_id",$data['id'])->update(["name"=>$data['name'],"phone"=>$data['phone'],"dealer_name"=>$data['cityId'],"models"=>$data['models'],"time"=>time()]);
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
     * 删除用户留资 宝沃
     * @param $id
     */
    public function BuserWdel($id)
    {
        try{

            DB::name("user_baowo")->where('dealer_id', $id)->delete();
            Db::name('lotuser')->where('userid', $id)->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除留资用户成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除留资用户成功'];

        }
        catch( PDOException $e)
        {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 删除用户留资 东标
     * @param [type] $id            [删除用户ID]
     * @param [type] $table         [用户表]
     * @param [type] $lotuser_table [用户奖品表]
     */
    public function DuserBdel($id,$table,$lotuser_table)
    {
        try{
            DB::name($table)->where('user_id', $id)->delete();
            Db::name($lotuser_table)->where('userid', $id)->delete();
            writelog(session('admin_uid'),session('username'),'用户【'.session('admin_username').'】删除留资用户成功(ID='.$id.')',1);
            return ['code' => 1, 'data' => '', 'msg' => '删除留资用户成功'];
        }
        catch( PDOException $e)
        {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


}