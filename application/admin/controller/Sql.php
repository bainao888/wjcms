<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
/*
 数据备份还原
 */
class Sql extends Base{
   protected $datadir =  './Public/backdata/';
   protected $prefix;
   public function __construct() {
       parent::__construct();
       $this->prefix = config('prefix');
   }
   /*
     获取数据表列表
    */
	public function back(){
        $dbtables = Db::query("SHOW TABLE STATUS LIKE '".$this->prefix."%'");
        //say($dbtables);
        $total = 0;
        foreach ($dbtables as $k => $v) {
            $dbtables[$k]['size'] = $this->format_bytes($v['Data_length'] + $v['Index_length']);
            $total += $v['Data_length'] + $v['Index_length'];
        }
        $this->assign('dataList', $dbtables);
        $this->assign('total', $this->format_bytes($total));
        $this->assign('tableNum', count($dbtables));
        return $this->fetch();
    }
     /*
      优化数据表
      */
     public function optimize() {
        $batchFlag = input('post.batchFlag', 0, intval);

        //批量删除
        if ($batchFlag) {
            $table = input('post.key');
        }else {
            $table[] = input('post.tableName' , '');
        }
         
        if (empty($table)) {
            $result['msg'] = '请选择要优化的表!';
            $result['code'] = 0;
            return json($result);
        }

        $strTable = implode(',', $table);
        //print_r($strTable);exit;
        if (!Db::query("OPTIMIZE TABLE {$strTable} ")) {
            $strTable = '';
        }
        $result['msg'] = '优化表成功!';
        $result['url'] = url('admin/sql/database');
        $result['code'] = 1;
        return json($result);
    }
     /*
       修复表
      */
     public function repair() {
        $batchFlag = input('post.batchFlag', 0, intval);
        //批量删除
        if ($batchFlag) {
            $table = input('post.key');
        }else {
            $table[] = input('post.tableName' , '');
        }

        if (empty($table)) {
            $result['msg'] = '请选择要修复的表!';
            $result['code'] = 0;
            $this->ajaxReturn($result);
        }

        $strTable = implode(',', $table);
        if (!Db::query("REPAIR TABLE {$strTable} ")) {
            $strTable = '';
        }
        $result['msg'] = '修复表成功!';
        $result['url'] = url('database');
        $result['code'] = 1;
        return json($result);
    }

     /*
      备份数据
      */
    public function backup(){
         
        
        $puttables = input('post.');
        //say($_POST['tables']);
        
        if(empty($puttables)) {
            $dataList = Db::query("SHOW TABLE STATUS LIKE '".$this->prefix."%'");
            
            foreach ($dataList as $row){
                $table[]= $row['Name'];
            }
        }else{
            $table=input('post.');
        }
        
        $sql = "-- PHP SQL Backup\n-- Time:".$this->toDate(time())."\n-- https://www.faryao.cn";
        $sql .= "-- E-mail:bainao888@163.com\n-- QQ:735613158\n\n";
        
        foreach($table['tables'] as $key=>$table) {
            $sql .= "--\n-- 表的结构 `$table`\n-- \n";
            $sql .= "DROP TABLE IF NOT EXISTS `$table`;\n";
            $info = Db::query("SHOW CREATE TABLE  $table");
            
            $sql .= str_replace(array('USING BTREE','ROW_FORMAT=DYNAMIC'),'',$info[0]['Create Table']).";\n";
            $result = Db::query("SELECT * FROM $table");
            if($result)$sql .= "\n-- \n-- 导出`$table`表中的数据 `$table`\n--\n";
            foreach($result as $key=>$val) {
                foreach ($val as $k=>$field){
                    if(is_string($field)) {
                        $val[$k] = '\''.$field.'\'';
                    }elseif($field==0){
                        $val[$k] = 0;
                    } elseif(empty($field)){
                        $val[$k] = 'NULL';
                    }
                }
                $sql .= "INSERT INTO `$table` VALUES (".implode(',', $val).");\n";
            }
        }
        $filename = empty($fileName)? date('YmdH').'_'.get_rand() : $fileName;
        if(!file_exists($this->datadir)) mkdir($this->datadir,true);
        $r= file_put_contents($this->datadir . $filename.'.sql', trim($sql));
        return json(['code'=>1,'msg'=>"成功备份数据库",'url'=>url('admin/sql/restore')]);
    }
     
     /*
     备份列表
     */
    public function restore(){
        $size = 0;
        $pattern = "*.sql";
        $filelist = glob($this->datadir.$pattern);
        $fileArray = array();
        foreach ($filelist  as $i => $file) {
            //只读取文件
            if (is_file($file)) {
                $_size = filesize($file);
                $size += $_size;
                $name = basename($file);
                $pre = substr($name, 0, strrpos($name, '_'));
                $number = str_replace(array($pre. '_', '.sql'), array('', ''), $name);
                $fileArray[] = array(
                    'name' => $name,
                    'pre' => $pre,
                    'time' => filemtime($file),
                    'size' => $_size,
                    'number' => $number,
                );
            }
        }
        if(empty($fileArray)) $fileArray = array();
        krsort($fileArray); //按备份时间倒序排列
        //say($fileArray);
        $this->assign('vlist', $fileArray);
        $this->assign('total', $this->format_bytes($size));
        $this->assign('filenum', count($fileArray));
        return $this->fetch();
    }

     /*
     执行还原数据库操作
      */
    public function restoreData() {
        header('Content-Type: text/html; charset=UTF-8');
        $filename = input('post.sqlfilepre');
        $file = $this->datadir.$filename;
       
        //读取数据文件
        $sqldata = file_get_contents($file);
        
        $sqlFormat = $this->sql_split($sqldata,$this->prefix);
        set_time_limit(0);
        foreach ((array)$sqlFormat as $sql){
            $sql = trim($sql);

            if (strstr($sql, 'CREATE TABLE')){
                preg_match('/CREATE TABLE `([^ ]*)`/', $sql, $matches);
                $ret = $this->excuteQuery($sql);
            }else{

                $ret =$this->excuteQuery($sql);
            }
        }
        $result['msg'] = '数据库还原成功!';
        $result['url'] = url('admin/sql/database');
        $result['code'] = 1;
        $this->ajaxReturn($result);
    }
    
    /*
    下载
     */
    public function downFile() {
        $file = input('param.file');
        $type = input('param.type');
        if (empty($file) || empty($type) || !in_array($type, array("zip", "sql"))) {
            $this->error("下载地址不存在");
        }
        $path = array("zip" => $this->datadir."zipdata/", "sql" => $this->datadir);
        $filePath = $path[$type] . $file;
        if (!file_exists($filePath)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($filePath);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
    }
    /*
    删除sql文件
     */
    public function delSqlFiles() {
        $file = input('post.sqlfilename');
       
        if (empty($file)) {
            $result['msg'] = '请选择要删除的sql文件!';
            $result['code'] = 0;
            return json($result);
        }

        $res = @unlink($this->datadir.'/' . $file);
       
        if($res){
            $result['msg'] = '删除成功!';
            $result['url'] = url('admin/sql/restore');
            $result['code'] = 1;
            return json($result);
        }else{
            $result['msg'] = '删除失败!';
            $result['code'] = 0;
            return json($result);
        }
    }
    /*
      安全过滤
     */
    public function excuteQuery($sql=''){
        if(empty($sql)) {$this->error('空表');}
        $queryType = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|TRUNCATE|REVOKE|LOCK|UNLOCK';
        if (preg_match('/^\s*"?(' . $queryType . ')\s+/i', $sql)) {            	
            $data['result'] = Db::execute($sql);
            $data['type'] = 'execute';
        }else {
            $data['result'] = Db::query($sql);
            $data['type'] = 'query';
        }
        $data['dberror'] = Db::getError();
        return $data;
    }

    public function  sql_split($sql,$tablepre) {
        if($tablepre != $this->prefix) $sql = str_replace($this->prefix, $tablepre, $sql);
        //$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8",$sql);

        if($r_tablepre != $s_tablepre) $sql = str_replace($s_tablepre, $r_tablepre, $sql);
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach($queriesarray as $query)
        {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach($queries as $query)
            {
                $str1 = substr($query, 0, 1);
                if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }

    /**
     * PHP格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
   private function format_bytes($size, $delimiter = '') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

    //时间日期转换
    private function toDate($time, $format = 'Y-m-d H:i:s') {
        if (empty ( $time )) {
            return '';
        }
        $format = str_replace ( '#', ':', $format );
        return date($format, $time );
    }

}