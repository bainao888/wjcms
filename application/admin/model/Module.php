<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Module extends Model 
{   

	  // 设置返回数据集的对象名
	  protected $resultSetType = 'collection';
    // 定义时间戳字段名
    protected $createTime = 'module_create_time';
    protected $updateTime = 'module_update_time';
    
    use SoftDelete;
    //删除字段名
    protected $deleteTime = 'module_delete_time';
    
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
	  //protected $autoWriteTimestamp = '';
	  protected $auto = [];
    protected $insert = ['module_create_time','module_status' => 1];  
    protected $update = ['module_update_time']; 
    protected $type = [
        'module_status'          =>  'integer',
        'module_create_time'     =>  'timestamp:Y/m/d',
        'module_update_time'     =>  'timestamp:Y-m-d',
    ];
    protected $prefix;
    
   
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
        $this->prefix = config('database.prefix');
    }
    /*
     获取列表
     */
    public function getList($where,$order){
    	// 查询状态为1的用户数据 并且每页显示10条数据
		$list = Module::where($where)->order($order)->paginate();
		// 获取分页显示
		return $list;
    	
    }

    public function getTrashList($where,$order){
        // 查询状态为1的用户数据 并且每页显示10条数据
     $list = Module::onlyTrashed()->where($where)->order($order)->paginate();
     // 获取分页显示
     return $list;
    }
    
    /*
      数据添加
      return id_module
     */
    public function dataAdd($data){
    
     $table = strtolower($data['module_name']);
     $createtable = $this->prefix.'document_'.$table;
     $type = $data['type'];
     //$showtable = "show tables";

     //查看数据是否有同样的名称
     $result = $this->getOne(['module_name'=>$data['module_name']]);
     if(!empty($result)){
       return  ['error'=>-1,'msg'=>'模型'.$table.'已存在'];
     }else{
    

       $createsql = $this->installModel($type,$table,$data['module_title']);
       $res = Db::execute($createsql);
       $module = new Module($data);
        // 过滤post数组中的非数据表字段数据
        $module->allowField(true)->save();
       //$module = Module->allowField(true)::create($data);
       //return $module->id_module;
       return  ['error'=>0,'msg'=>'模型'.$table.'创建成功'];

     }
    }
    /*
     数据更新
     return id_module
     */
    public function dataEdit($data){
    	 $module =Module::update($data);
        return $module->id_module;

    }

    /*
      删除数据
     */
    public function dataDel($data,$bool = false){
      //软删除 只是更新删除字段时间 并不会删除数据
      //
      //
      if($bool == true){
        Module::destroy($data['id'],true);
        $createtable = $this->prefix.'document_'.$data['name'];
        $sql = "DROP TABLE  `{$createtable}`";
        Db::execute($sql);
      }else{
         Module::destroy($data['id']);
      }
      return  ['error'=>0,'msg'=>'表'.$data['name'].'删除成功'];
    }
    
    /**
     * 删除多个数据
     * @return [type] [description]
     */
    public function dataDelmore($data){
      Module::destroy($data);
    }

    /**
     * 恢复数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function dataRestore($id){
     Module::restore(['id_module'=>$id]);
     return  ['error'=>0,'msg'=>'恢复成功'];
    }
    /**
     * [改变状态]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function dataChangeStatus($data){
        $info = $this->getOne($data,'module_status');
        
        if(!$info[0]){
          $data['module_status'] = 1;
        }else{
          $data['module_status'] = 0;
        }
        //say($data);
        $module =Module::update($data);
        return ['error'=>0,'msg'=>'状态改变成功'];
    }

    /*
      根据查询内容获取一条信息
     */
    public function getOne($where,$field=null){
    	$module = Module::where($where)->column($field);
    	return $module;
    }
    /**
     * [installModel description]
     * @param  [string] $type  [模块类型]
     * @param  [string] $table [数据表]
     * @return [type]        [description]
     */
    protected function installModel($type,$table,$name){
     
     $createtable = $this->prefix.'document_'.$table;
     $comment = $name;
      switch ($type) {
        //文档模块
        case 'article':
          $createdocument  = <<<sql
CREATE TABLE IF NOT EXISTS `{$createtable}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text  NOT NUll COMMENT '文章内容',
  `template` varchar(100)  NOT NULL COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned  NOT NULL COMMENT '文档收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='{$comment}';
sql;
          # code...
          break;
        //下载模块
        case 'download':
          
          $createdocument  = <<<sql
CREATE TABLE IF NOT EXISTS `{$createtable}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text  NOT NUll COMMENT '下载详细描述',
  `template` varchar(100)  NOT NULL COMMENT '详情页显示模板',
  `file_id` int(10) unsigned  NOT NULL COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='{$comment}';
sql;
          break;
        //图片模块
        case 'picture':
          $createdocument  = <<<sql
CREATE TABLE IF NOT EXISTS `{$createtable}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text  NOT NUll COMMENT '图片描述',
  `template` varchar(100)  NOT NULL COMMENT '详情页显示模板',
  `file_id` int(10) unsigned  NOT NULL COMMENT '图片ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='{$comment}';
sql;
          break;
        //产品模块
        case 'goods':
         $createtable = $this->prefix.'goods_'.$table;
         $createdocument  = <<<sql
CREATE TABLE IF NOT EXISTS `{$createtable}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text  NOT NUll COMMENT '图片描述',
  `template` varchar(100)  NOT NULL COMMENT '详情页显示模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='{$comment}';
sql;
          break;
        //新闻模块
        case 'news':
       $createdocument  = <<<sql
CREATE TABLE IF NOT EXISTS `{$createtable}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text  NOT NUll COMMENT '新闻内容',
  `template` varchar(100)  NOT NULL COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned  NOT NULL COMMENT '文档收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='{$comment}';
sql;
          # code...
          break;
        //文档模块
        default:
         $createdocument = '';
          break;
      }
      return $createdocument;
      
    }

    /**
     * 获取字段信息
     * @param  [type] $table [description]
     * @return [type]        [description]
     */
    public function getField($table){
      $table = $this->prefix.$table;
      $fields = Db::getFields($table);
      return $fields;
    }
}