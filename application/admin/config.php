<?php
return [
    
    'url_route_on' => true,
    'url_route_must'  =>  false,
    //模板参数替换
    'view_replace_str' => array(
        '__CSS__' => '/Flow/public/static/admin/css',
        '__JS__'  => '/Flow/public/static/admin/js',
        '__IMG__' => '/Flow/public/static/admin/images',
		'__CS__'  =>'/Flow/public/static/css',
        '__JSS__'  =>'/Flow/public/static/js',
        '__IMAGE__'  =>'/Flow/public/static/images',
        '__PLUG__'  =>'/Flow/public/static/plugins',
        '__js__'   =>'/Flow/public/statisc/js',
        '__css__'   =>'/Flow/public/statisc/css',
        '__img__'   =>'/Flow/public/statisc/img',
        '__lay__'   =>'/Flow/public/statisc/layui',
        '__font__'   =>'/Flow/public/statisc/Font-Awesome/css',
        '__common__'   =>'/Flow/public/statisc/layui/lay/modules/extendplus',
    ),
    // 'template' => [
    //     // 模板引擎类型 支持 php think 支持扩展
    //     // 'view_path'    => './application/admin/view/default/',
    //     'view_path'    => './template/admin/',
    //     //'theme_name'   =>'default',
    //     'theme_name'   =>'default',
    // ],
];
