<?php
namespace App\Handlers;

use Image;

class ImageUploadHandler{
    protected $allowed_ext = ['jpg','gif','png','jpeg'];

    public function save($file,$folder,$file_prefix,$max_width){
        //构建储存文件夹的规则
        $folder_name = 'uploads/images/'.$folder.'/'.date('Ym/d',time());

        //文件具体的储存路径
        $file_path = public_path().'/'.$folder_name;

        //获取文件的后缀名，粘贴过来的图片没有后缀名
        $extension =strtolower($file->getClientOriginalExtension())?:'png';

        //拼接文件名，加前缀是为了辨析度，前缀可以是相关数据的模型ID
        $filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

        //如果上传的不是图片讲终止操作
        if(!in_array($extension,$this->allowed_ext)){
            return false;
        }

        //将图片移动到我们目标储存路径
        $file->move($file_path,$filename);
        
        //如果图片进行了宽度限制就裁剪
        if($max_width && $extension != 'gif'){
            $this->reduceImage($file_path.'/'.$filename,$max_width);
        }

        return [
            'path' =>config('app.url')."/$folder_name/$filename",
        ];
    }

    public function reduceImage($file_path,$max_width){
        //实例化，传参是文件的物理文件
        $images = Image::make($file_path);
        
        $images->resize($max_width,null,function($contain){
            //设定$max_width,等比例缩放
            $contain->aspectRatio();
            //防止截图时图片尺度变大
            $contain->upsize();
        });

        //对图片修改进行保存
        $images->save();
    }
}