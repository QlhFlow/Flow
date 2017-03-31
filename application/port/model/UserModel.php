<?php

namespace app\port\model;
use think\Model;
use think\Db;
class UserModel extends Model
{
    protected $table = "flow_project";
    protected $name = 'project';

    /**
     * 用户添加
     */
    public function ProjectAdd($data,$table)
    {
    	//过滤字段添加
    	return DB::name($table)->insert($data);
    }
}