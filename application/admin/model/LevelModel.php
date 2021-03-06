<?php
/**
 * Created by PhpStorm.
 * User: SEO-9
 * Date: 2017/1/17
 * Time: 10:12
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class LevelModel extends Model
{
    protected $name='user_leval';

    /*
     * 获取所有会员等级列表
     */
    public function getLevalList(){
        return $this->order('id asc')->select();
    }

    /*
     * 获取制定的等级列表信息
     */
    public function getLevelInfo(){
        return $this->find();
    }
    /*
     * 添加会员等级
     */
    public function addMemberLeavl($param){
        try{
            $result = $this->insert($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '会员等级添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 编辑会员等级
     */
    public function editMemberLeavl($param){
        try{
            $result = $this->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '会员等级编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 删除会员等级
     */
    public function delMemberLeval($id){
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除会员等级成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}