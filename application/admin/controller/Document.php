<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Document extends Base{
    
    /*
      列表
     */
	public function lists(){	
        
        $m = 'list_'.input('param.m/s');
        
        //dump(input('param.'));
        $Document = model('Document');
        $list = $Document->getList(['module_id'=>input('param.id/d')],['id'=>'asc']);

        $this->assign('list',$list);
		return $this->fetch($m);
	}
    /*
      添加
     */
	public function add(){
       // dump(input("param.id"));
        if(request()->isAjax()){
            $data = input('post.');
            $validate = validate('Document');
            //$data = input('post.');
            if(!$validate->check($data)){
            $this->error($validate->getError());
            }else{
                $module = model('Document');
                $data = [
                    'uid'         =>1,
                    'module_id'   =>$data['module_id'],
                    'category_id' =>$data['cate_id'],
                    'thumb_id'    =>$data['thumb_id'],
                    'title'       =>$data['title'],
                    'description' =>$data['desc'],
                    'is_new'      => !empty($data['is_new'])?$data['is_new']:0,
                    'is_top'      => !empty($data['is_top'])?$data['is_top']:0,
                    'content'     =>$data['content'],
                    'template'    =>$data['template'],
                    'module'      =>$data['module'],
                ];

               
                $res = $module->dataAdd($data);
                return $res;
            }

        }
        $m = 'add_'.input('param.m/s');
        $cate = 'cate_'.input('param.m/s');
        $catelist = get_doc_cate($cate,'',3,'id,title,pid');
        $this->assign('catelist',$catelist);
		return $this->fetch($m);
	}
    /*
      编辑
     */
	public function edit(){
        
        if(request()->isAjax()){
            $data = input('post.');
            $validate = validate('Document');
            //$data = input('post.');
            if(!$validate->check($data)){
            $this->error($validate->getError());
            }else{
                $module = model('Document');
                $Data = [
                    'uid'         =>1,
                    'module_id'   =>$data['module_id'],
                    'category_id' =>$data['cate_id'],
                    'thumb_id'    =>$data['thumb_id'],
                    'old_thumb_id'=>$data['old_thumb_id'],
                    'title'       =>$data['title'],
                    'description' =>$data['desc'],
                    'is_new'      => !empty($data['is_new'])?$data['is_new']:0,
                    'is_top'      => !empty($data['is_top'])?$data['is_top']:0,
                    'status'      => !empty($data['status'])?$data['status']:0,
                    'content'     =>$data['content'],
                    'template'    =>$data['template'],
                    'module'      =>$data['module'],
                ];
                //say($data);
                $id = $data['id'];
                
               // say($data);
                $res = $module->dataEdit($Data,$id);
                return $res;
            }

        }
        $tpl = input('param.m/s');
        $id = input('param.id/d');
        $cate = 'cate_'.input('param.m/s');
        $table = 'document_'.$tpl;
        $info = db('document')->field(['id'=>'docid','title','is_top','is_new','description','status','listorder','category_id','module_id','thumb_id'])->find($id);
        if($info['thumb_id']!=0){


        $info = db('document')->alias('d')->join('__IMAGES__ img','d.thumb_id = img.id')
                ->field(['d.id'=>'docid','img.url','title','is_top','is_new','description','status','listorder','category_id','module_id','thumb_id'])
                ->fetchSql(false)
                ->find($id);
        }
        $content = db($table)->find($id);//内容
        $catelist = get_doc_cate($cate,'',3,'id,title,pid');
        //dump($info);
        $this->assign('catelist',$catelist);
        $this->assign('info',$info);
        $this->assign('content',$content);
		return $this->fetch('edit_'.$tpl);
	}
    /*
     删除
     */
	public function del(){
        
		return $this->fetch();
	}



	public function upload(){

        $file = upload('file','document');
        if($file[0]['error']==0){

          $id = Db::name('images')->insertGetId(['url'=>$file[0]['result']]);
          return ['error'=>0,'msg'=>'upload success','result'=>['id'=>$id,'path'=>$file[0]['result']]];
        }
        
		
	}


    public function ueditorUpload(){
        
        $file = upload('file','ueditor');

        if($file[0]['error']==0){

          //$id = Db::name('images')->insertGetId(['url'=>$file[0]['result']]);

          return json(["state" => 'SUCCESS',"url" => get_host().$file[0]['result']]);
          
        }
    }



}