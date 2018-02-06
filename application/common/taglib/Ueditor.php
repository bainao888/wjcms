<?php
/*
 * 自定义标签
 */
namespace app\common\taglib;
use think\template\TagLib;
class Ueditor extends TagLib{

    // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
    protected $tags = [
        'ueditor'=> ['attr'=>'name,id,toolbar,iscode,width,height,url','close'=>1],
    ];
    /**
    *引入ueidter编辑器
    *@param string $tag  name:表单name content：编辑器初始化后 默认内容
    * 格式：<ueditor id="editor" name="editors" iscode="0" toolbar="sample" height="500px" url="Url()方法或http://|https">内容</ueditor>
    */
   public function tagUeditor($tag,$content){
       
       $tool['sample'] = <<<php
            [['undo', 'redo', '|',
            'bold', 'italic', 'underline',  '|', 'cleardoc', '|', 'fontfamily', 'fontsize', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'emotion',
        ]]
php;
       $tool['weixin'] = <<<php
            [['undo', 'redo', '|','simpleupload','|','insertimage',
            'bold', 'italic', 'underline',  '|', 'cleardoc', '|', 'fontfamily', 'fontsize', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'fullscreen', 
        ]]
php;

       $tool['full'] = <<<php
            [['fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'pagebreak', 'template', 'background', '|',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'drafts'
        ]]
php;

       $bar = !empty($tag['toolbar'])?$tag['toolbar']:'sample';
       $toolbars = $tool[$bar];
       $name    = $tag['name'];//name
       $id      = $tag['id'];//id
       //解析生成url函数  U("")
       //上传图片的地址
       
      if(preg_match('/^(http|https):\/\/(www)?.*.(htm|html|asp|php)?/', $tag['url'], $matches)){
         $url = $matches[0];
       }else{
         $url = get_host().url($tag['url']);
       }
       //say($url);
       $height  = !empty($tag['height'])?$tag['height']:'667px';
       $width   = !empty($tag['width'])?$tag['width']:'375px';
       $source  = intval($tag['iscode'])?intval($tag['iscode']):0; //是否打开源码 默认0不打开 1打开
       $ueditor    = <<<php
       <script type="text/javascript" charset="utf-8" src="__STATIC__/ueditor/ueditor.config.js"></script>
       <script type="text/javascript" charset="utf-8" src="__STATIC__/ueditor/ueditor.all.min.js"> </script>
       <script type="text/javascript" charset="utf-8" src="__STATIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
       <script id="$id" type="text/plain" name="$name" style="width:$width;height:$height;overflow:auto;">$content</script>  
       <script type="text/javascript">
	     var ue = UE.getEditor("$id",{
         toolbars: $toolbars,
        //serverUrl:'',
         sourceEditorFirst :$source,//是否进入源码
         });
	     UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
	    UE.Editor.prototype.getActionUrl = function(action) {
         if (action == "uploadimage" || action == "catchimage") {
	        return "$url";
	    }else {
	        return this._bkGetActionUrl.call(this, action);
	    }
	   }
        </script>       
php;

        return $ueditor;
   }
}
