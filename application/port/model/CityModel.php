<?php
/**
 * 极客之家 高端PHP - 经销商模块
 *
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\port\model;
use think\Model;
use think\Db;
class CityModel extends Model
{
    protected $table = 'dealer_dongbiao';

    /**
     * 查询一级城市
     * @return [type] [description]
     */
    public function city_father()
    {
    	return DB::name($this->table)->where("pid",1)->select();
    }

    /**
     * 查询子级城市数据
     * @return [type] [description]
     */
    public function city_sublevel($id)
    {
    	return Db::name($this->table)->where('dealer_id',$id)->select();
    }

    /**
     * 查询城市经销商全部数据
     */
    public function DealerAll()
    {
        $res = DB::name($this->table)->where("pid",0)->select();
        foreach ($res as $key => $val) {
            $res[$key]['city'] = DB::name($this->table)->where("pid",$val['dealer_id'])->select();
            foreach ($res[$key]['city'] as $kk => $vv) {
                $res[$key]['city'][$kk]['dealer'] = DB::name($this->table)->where("pid",$vv['dealer_id'])->select();
            }
        }

        return $res;
        
    }

    /**
     * 查询所有城市
     */
    public function CityData()
    {
       $res = DB::name("city")->where("parent_id",1)->select();
        foreach ($res as $key => $val) {
            $res[$key]['city'] = DB::name("city")->where("parent_id",$val['region_id'])->select();
            foreach ($res[$key]['city'] as $kk => $vv) {
                $res[$key]['city'][$kk]['dealer'] = DB::name("city")->where("parent_id",$vv['region_id'])->select();
            }
        }

        return $res; 
    }

}