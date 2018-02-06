<?php
/*
  函数
 */

/**
 * PHP格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

//文件单位换算
function byte_format($input, $dec=0){
    $prefix_arr = array("B", "KB", "MB", "GB", "TB");
    $value = round($input, $dec);
    $i=0;
    while ($value>1024) {
        $value /= 1024;
        $i++;
    }
    $return_str = round($value, $dec).$prefix_arr[$i];
    return $return_str;
}

//时间日期转换
function toDate($time, $format = 'Y-m-d H:i:s') {
    if (empty ( $time )) {
        return '';
    }
    $format = str_replace ( '#', ':', $format );
    return date($format, $time );
}
/*
获取菜单列表
@param $where 条件搜索 
@param $level 循环级别
@param $field 显示字段
*/
function get_menu($where=['pid'=>0],$level=3,$field="*"){
	set_time_limit(0);
	if(is_array($where)){
	    $map = $where;
	}else{
	    $map['pid'] = $where;
	}
	$data = array();
	if($level>0){
	    $data = db('menu')->where($map)->order('listorder asc')->field($field)->select();
	    if(!empty($data)){
	    foreach ($data as $key => $val) {
	    	if(!empty($val['url'])){
	    		$data[$key]['url'] = urldecode(get_host().url($val['url'],$val['param']));
	    	}
	        if($map['pid']==$val['pid']){
	            $count = db('menu')->where(['pid'=>$val['id']])->count();
	            if($count>0){
	                $data[$key]['child'] = get_menu(['pid'=>$val['id']],$level-1,$field);
	            }
	        }
	        
	    }
	  }else{
	    $data = null;
	  }
	}
	return $data;
}
/**
 * 文档获取分类
 * @param  [type]  $table [description]
 * @param  array   $where [description]
 * @param  integer $level [description]
 * @param  string  $field [description]
 * @return [type]         [description]
 */
function get_doc_cate($table,$where=['pid'=>0],$level=3,$field="*"){
	set_time_limit(0);
	if(is_array($where)){
	    $map = $where;
	}else{
	    $map['pid'] = $where;
	}
	$data = array();
	if($level>0){
	    $data = db($table)->where($map)->order('id asc')->field($field)->select();
	    if(!empty($data)){
	    foreach ($data as $key => $val) {
	        if($map['pid']==$val['pid']){
	            $count = db($table)->where(['pid'=>$val['id']])->count();
	            if($count>0){
	                $data[$key]['child'] = get_doc_cate($table,['pid'=>$val['id']],$level-1,$field);
	            }else{
	            	$data[$key]['child'] = [];
	            }
	        }
	        
	    }
	  }else{
	    $data = null;
	  }
	}
	return $data;
}

