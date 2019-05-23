<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    //所有权限都通过
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,'.Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:png,jpeg,gif,bmp|dimensions:min_width=208,min_height=208',
        ];
    }

    public function messages(){
        return [
            'avatar.mimes' => '图片格式只能为png,jpeg,gif,bmp',
            'avatar.dimensions' => '图片的宽度、高度像素均不低于208',
            'name.unique' => '该用户名已被占用',
            'name.regex' => '用户名支持英文、数字、横杠和下划线',
            'name.between' => '用户名字符数介于3-25个',
            'name.required' => '用户名不能为空',
        ];
    }
}
