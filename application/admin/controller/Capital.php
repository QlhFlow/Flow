<?php
/**
 * 极客之家 高端PHP - 用户留资信息列表
 * @copyright  Copyright (c) 2016 QIN TEAM (http://www.qlh.com)
 * @license    GUN  General Public License 2.0
 * @version    Id:  Type_model.php 2016-6-12 16:36:52
 */
namespace app\admin\controller;
use app\admin\model\ArticleModel;
use app\admin\model\ArticleCateModel;
use app\admin\model\CapitalModel;
use think\Db;
use com\IpLocation;
class Capital extends Base
{
	/**
	 * 东标-火辣健身用户留资列表
	 * @return [type] [description]
	 */
	function user_dongbiao()
	{
        $serchName = input("param.phone");
        if(isset($serchName) || !empty($serchName)){
            $where = "phone='$serchName'";
        }
        else
        {
            $where = 1;
        }
        $project_tablename = "user_dongbiao";
        $lotuser_table = "lotuser_dongbiao";//奖品表
		$key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['admin_id'] =  $key;          
        }  
		$res = Db::name("user_dongbiao")->order("user_id desc")->select();
            
            $Nowpage = input('get.page') ? input('get.page'):1;
	        $limits = 10;// 获取总条数
	        $count = Db::name('user_dongbiao')->where($where)->count();//计算总页面
	        $allpage = intval(ceil($count / $limits));
	        $lists = Db::name('user_dongbiao')->where($where)->page($Nowpage, $limits)->order('user_id desc')->select();     
	        //查询经销商
            foreach ($lists as $key => $val) {
                $array = Db::name("dealer_dongbiao")->where("dealer_id",'in',$val['dealer_name'])->select();
                $arr = array(); 
                foreach ($array as $kk => $vv) {
                    $arr[] = $vv['dealer_name'];
                }
                $string = join(",",$arr);
                $lists[$key]['dealer'] = mb_strlen($string, 'utf-8') > 9 ? mb_substr($string, 0, 9, 'utf-8').'....' :$string;
        		$lists[$key]['time'] = date("Y-m-d H:i:s",$val['time']);

                //查询用户获得的奖品
                $lists[$key]['lotter'] = 0;
                $lotterArray = DB::name("user_dongbiao")->alias("d")->join("flow_lotuser_dongbiao l","l.userid=d.user_id")->field("d.user_id,l.lotid,l.userid")->where("d.user_id",$val['user_id'])->select();
                foreach ($lotterArray as $k => $v) {
                    $res = DB::name("lottery_dongbiao")->where("id",$v['lotid'])->field("name,id")->select();
                    foreach ($res as $kkk => $vvv) {
                        $lists[$key]['lotter'] = $vvv['name'];
                    }
                }
				
				//查询车型
				$ModelCar = DB::name("car_series")->where("car_id",$val['models'])->field("car_id,car_name")->find();

				$lists[$key]['models'] = $ModelCar['car_name'];
            }
	        $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
	        $this->assign('Nowpage', $Nowpage); //当前页
	        $this->assign('allpage', $allpage); //总页数 
	        $this->assign('count', $count);
	        $this->assign("search_user",$res);
	        $this->assign('val', $key);
	        $this->assign('table', $project_tablename);
            $this->assign('lotuser_table', $lotuser_table);
            $this->assign("phone",$serchName);//搜索条件
	        if(input('get.page')){
	            return json($lists);
	        }
	        return $this->fetch('dongbiaouser_list');
	}

    /**
     * 东标-美团用户留资列表
     * @return [type] [description]
     */
    function user_db_meituan()
    {
        $serchName = input("param.phone");
        if(isset($serchName) || !empty($serchName)){
            $where = "phone='$serchName'";
        }
        else
        {
            $where = 1;
        }
        $project_tablename = "user_db_meituan";//用户表
        $lotuser_table = "lotuser_db_meituan";//奖品表
        $key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['admin_id'] =  $key;          
        }  
        $res = Db::name("user_db_meituan")->order("user_id desc")->select();
            
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = 10;// 获取总条数
            $count = Db::name('user_db_meituan')->where($where)->count();//计算总页面
            $allpage = intval(ceil($count / $limits));
            $lists = Db::name('user_db_meituan')->where($where)->page($Nowpage, $limits)->order('user_id desc')->select();     

            //查询经销商
            foreach ($lists as $key => $val) {
                $array = Db::name("dealer_dongbiao")->where("dealer_id",'in',$val['dealer_name'])->select();
                $arr = array(); 
                foreach ($array as $kk => $vv) {
                    $arr[] = $vv['dealer_name'];
                }
                $string = join(",",$arr);
                $lists[$key]['dealer'] = mb_strlen($string, 'utf-8') > 9 ? mb_substr($string, 0, 9, 'utf-8').'....' :$string;
                $lists[$key]['time'] = date("Y-m-d H:i:s",$val['time']);

                //查询用户获得的奖品
                $lists[$key]['lotter'] = 0;
                $lotterArray = DB::name("user_db_meituan")->alias("d")->join("flow_lotuser_db_meituan l","l.userid=d.user_id")->field("d.user_id,l.lotid,l.userid")->where("d.user_id",$val['user_id'])->select();
                foreach ($lotterArray as $k => $v) {
                    $res = DB::name("lottery_db_meituan")->where("id",$v['lotid'])->field("name,id")->select();
                    foreach ($res as $kkk => $vvv) {
                        $lists[$key]['lotter'] = $vvv['name'];
                    }
                }
                
                //查询车型
                $ModelCar = DB::name("car_series")->where("car_id",$val['models'])->field("car_id,car_name")->find();
                $lists[$key]['models'] = $ModelCar['car_name'];
            }
            // print_r($lists);die;
            $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
            $this->assign('Nowpage', $Nowpage); //当前页
            $this->assign('allpage', $allpage); //总页数 
            $this->assign('count', $count);
            $this->assign("search_user",$res);
            $this->assign('val', $key);
            $this->assign('table', $project_tablename);
            $this->assign('lotuser_table', $lotuser_table);
            $this->assign("phone",$serchName);//搜索条件
            if(input('get.page')){
                return json($lists);
            }
            return $this->fetch();
    }

    /**
     * 东标-永乐票务用户留资列表
     * @return [type] [description]
     */
    function user_db_yongle()
    {
        $serchName = input("param.phone");
        if(isset($serchName) || !empty($serchName)){
            $where = "phone='$serchName'";
        }
        else
        {
            $where = 1;
        }
        $project_tablename = "user_db_yongle";
        $lotuser_table = "lotuser_db_yongle";//奖品表
        $key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['admin_id'] =  $key;          
        }  
        $res = Db::name("user_db_yongle")->order("user_id desc")->select();
            
            $Nowpage = input('get.page') ? input('get.page'):1;
            $limits = 10;// 获取总条数
            $count = Db::name('user_db_yongle')->where($where)->count();//计算总页面
            $allpage = intval(ceil($count / $limits));
            $lists = Db::name('user_db_yongle')->where($where)->page($Nowpage, $limits)->order('user_id desc')->select();     

            //查询经销商
            foreach ($lists as $key => $val) {
                $array = Db::name("dealer_dongbiao")->where("dealer_id",'in',$val['dealer_name'])->select();
                $arr = array(); 
                foreach ($array as $kk => $vv) {
                    $arr[] = $vv['dealer_name'];
                }
                $string = join(",",$arr);
                $lists[$key]['dealer'] = mb_strlen($string, 'utf-8') > 9 ? mb_substr($string, 0, 9, 'utf-8').'....' :$string;
                $lists[$key]['time'] = date("Y-m-d H:i:s",$val['time']);

                //查询用户获得的奖品
                $lists[$key]['lotter'] = 0;
                $lotterArray = DB::name("user_db_yongle")->alias("d")->join("flow_lotuser_db_yongle l","l.userid=d.user_id")->field("d.user_id,l.lotid,l.userid")->where("d.user_id",$val['user_id'])->select();
                foreach ($lotterArray as $k => $v) {
                    $res = DB::name("lottery_db_yongle")->where("id",$v['lotid'])->field("name,id")->select();
                    foreach ($res as $kkk => $vvv) {
                        $lists[$key]['lotter'] = $vvv['name'];
                    }
                }
                
                //查询车型
                $ModelCar = DB::name("car_series")->where("car_id",$val['models'])->field("car_id,car_name")->find();
                $lists[$key]['models'] = $ModelCar['car_name'];
            }
            // print_r($lists);die;
            $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
            $this->assign('Nowpage', $Nowpage); //当前页
            $this->assign('allpage', $allpage); //总页数 
            $this->assign('count', $count);
            $this->assign("search_user",$res);
            $this->assign('val', $key);
            $this->assign('table', $project_tablename);//用户表
            $this->assign('lotuser_table', $lotuser_table);//奖品表
            $this->assign("phone",$serchName);//搜索条件
            if(input('get.page')){
                return json($lists);
            }
            return $this->fetch();
    }

	/**
	 * 宝沃留资列表
	 * @return [type] [description]
	 */
	function user_baowo()
	{
        $serchName = input("param.phone");
        if(isset($serchName) || !empty($serchName)){
            $where = "phone='$serchName'";
        }
        else
        {
            $where = 1;
        }
        
		$project_tablename = "user_baowo";
		$key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['admin_id'] =  $key;
        }  
		$arr = Db::name("user_baowo")->where($where)->order("dealer_id desc")->select(); //获取用户列表      
       // foreach ($arr as $kk => $vv) {
       //      $arr[$kk]['time'] = date("Y-m-d",$vv['time']);
       //  }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数
        $count = Db::name('user_baowo')->where($where)->count();//计算总页面
        // echo $count;die;
        $allpage = intval(ceil($count / $limits));
        $lists = Db::name('user_baowo')->where($where)->page($Nowpage, $limits)->order('dealer_id desc')->select();
         foreach ($lists as $kk => $vv) {
        	$lists[$kk]['time'] = date("Y-m-d H:i:s",$vv['time']);
            
            //查询用户获得的奖品
            $lists[$kk]['lotter'] = 0;
            $lotterArray = DB::name("user_baowo")->alias("b")->join("flow_lotuser l","l.userid=b.dealer_id")->field("b.dealer_id,l.lotid,l.userid")->where("b.dealer_id",$vv['dealer_id'])->select();
            foreach ($lotterArray as $k => $v) {
                $res = DB::name("lottery")->where("id",$v['lotid'])->field("name,id")->select();
                foreach ($res as $kkk => $vvv) {
                    $lists[$kk]['lotter'] = $vvv['name'];
                }
            }
        }
        // print_r($lists);die;
        $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数 
        $this->assign('count', $count);
        $this->assign("search_user",$arr);
        $this->assign('val', $key);
        $this->assign("phone",$serchName);
        $this->assign('table', $project_tablename);
        if(input('get.page')){
            return json($lists);
        }
        return $this->fetch('baowouser_list');
	}


    /*
     * 更改留资用户状态列表
     * 宝沃
     */
    public function BstateWleval(){
        $id = input('param.id');
        $status = Db::name('user_baowo')->where(array('dealer_id'=>$id))->value('status');//判断当前状态情况
        if($status == 1)
        {
            $flag = Db::name('user_baowo')->where(array('dealer_id'=>$id))->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = Db::name('user_baowo')->where(array('dealer_id'=>$id))->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }

    /*
     * 更改留资用户状态列表
     * 东标
     */
    public function DstateBleval(){
        $id = input('param.id');

        $status = Db::name('user_dongbiao')->where(array('user_id'=>$id))->value('status');//判断当前状态情况
        if($status == 1)
        {
            $flag = Db::name('user_dongbiao')->where(array('user_id'=>$id))->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = Db::name('user_dongbiao')->where(array('user_id'=>$id))->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    }

    /**
     * [BuserWedit 编辑用户 宝沃]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function BuserWedit()
    {
        $Capital = new CapitalModel();

        if(request()->isAjax()){

            $param = input('post.');
            $province = strtok($param['province'], '_');  //省
            $city = strtok($param['city'], '_');  //市
            // $cityId = $province.",".$city.",".$param['area'];//省市县id
            $cityId = $province.",".$city;//省市县id

            $arr = DB::name("city")->where("region_id","in",$cityId)->select();
            foreach($arr as $key => $val){
                $string[] = $val['region_name'];
            }
            $param['citys'] = implode(",",$string);//省市县名称

            $flag = $Capital->BuserWedit($param);
            
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $data = $Capital->BuserWfind($id);

        $this->assign("dealer_id",$id);
        $this->assign("data",$data);
        return $this->fetch();
    }

    /**
     * [BuserWedit 编辑用户 东标]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function DuserBedit()
    {
        $Capital = new CapitalModel();
        if(request()->isAjax()){
            $param = input('post.');
            $table = $param['table'];
            unset($param['table']);
            $province = strtok($param['province'], '_');  //省
            $city = strtok($param['city'], '_');  //市
            $param['cityId'] = $province.",".$city.",".$param['area'];//省市县id
            $flag = $Capital->DuserBedit($param,$table);
            
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        
        $id = input('param.id');
        $table = input('param.table');
        $data = $Capital->DuserBfind($id,$table);
        $this->assign("dealer_id",$id);
        $this->assign("data",$data);
        $this->assign("table",$table);
        return $this->fetch();
    }


    /**
     * [UserDel 删除用户]
     * 宝沃
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function BuserWdel()
    {
        $id = input('param.id');
        $Capital = new CapitalModel;
        $flag = $Capital->BuserWdel($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [UserDel 删除用户]
     * 东标
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function DuserBdel()
    {
        $id = input('param.id');
        $table = input('param.table');
        $lotuser_table = input('param.lotuser_table');
        $Capital = new CapitalModel;
        $flag = $Capital->DuserBdel($id,$table,$lotuser_table);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
}