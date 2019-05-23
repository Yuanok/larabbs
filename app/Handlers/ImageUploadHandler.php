<?php
namespace App\Handlers;

class ImageUploadHandler{
    protected $allowed_ext = ['jpg','gif','png','jpeg'];

    public function save($file,$folder,$file_prefix){
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

        return [
            'path' =>config('app.url')."/$folder_name/$filename",
        ];
    }
}