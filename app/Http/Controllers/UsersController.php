<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function show(User $user){
        return view('users.show',['user'=>$user]);
    }

    public function edit(User $user){
        return view('users.edit',['user'=>$user]);
    }
    public function update(UserRequest $request,User $user,ImageUploadHandler $uploader){
        $data = $request->all();

        if($request->avatar){
            $result =$uploader->save($request->avatar,'avatar',$user->id,416);
            //若上传的不是图片，返回false
            if($result){
                $data['avatar'] = $result['path'];
            }
            $user->update($data);
            return redirect()->route('users.show',$user->id)->with('sucess','个人资料更新成功~');
        }
    }
}   
