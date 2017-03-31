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

class BaseModel extends Model
{
    protected $name = 'table';
    
    

    /**
     * 根据表名查询对应用户数据
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function user_databaseName($table)
    {
    	
    }
}