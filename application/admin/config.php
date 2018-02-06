<?php
return [

	//分页配置
    'paginate'               => [
        'type'      => '\org\page\Amazeui',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    'template'               => [
            // 预先加载的标签库
            'taglib_pre_load'     =>    'app\common\taglib\Ueditor', 
          
        ],

];