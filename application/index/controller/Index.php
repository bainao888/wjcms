<?php
namespace app\index\controller;
use think\Controller;
#use think\Loader;
use think\Db;
class Index extends Controller
{
    public function index()
    {
        //$user = session('user');
       // dump($user);
         //$info = Db::name('module')->where('id_module',9)->find();
    	//$info = ['address'=>'南海区丹灶镇横江公园旁边有需要维修','tel'=>'0757-85436266','error'=>0];
        //$info = ['address'=>'南海区丹灶镇横江虚','tel'=>'0757-85436266','error'=>200];
        //return json($info,200);
        return $this->fetch();
    }

    public function upload(){
    	
    	$file = upload('file','ads');
    	//$str = '\public\uploads\ads\20180109\b3e3ad97f5e89601c6eb027491791275.jpg';
    	//$res = delimg($str);
    	if($file[0]['error']==0){
    		$this->success('上传成功',url('index/index/index'));
    	}
    	//say($file);
    }

    public function handel(){
       
    }

    
}
