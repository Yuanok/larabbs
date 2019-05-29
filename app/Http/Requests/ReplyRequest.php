<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
      return [
        'content' =>  'required|min:2'
      ];
    }

    public function messages()
    {
        return [
            'content.required' => '评论不能为空且不少于两个字'
        ];
    }
}
