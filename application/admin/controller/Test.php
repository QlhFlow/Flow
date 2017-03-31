<?php

namespace app\admin\controller;
use think\Db;
use think\Controller;
class Test extends Controller
{
	public function add()
	{
		if(input("param.")){
			$data = input("param.");
			print_r($data);
		}
		else
		{
			return $this->fetch('index');
		}
	}
}
