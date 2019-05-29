<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Reply $reply)
    {
        //    当前登录用户ID与创建帖子的用户ID相同       当前用户ID与该评论的用户ID相同
        return $user->isAuthorOf($reply->topic)   ||  $user->isAuthorOf($reply);
    }
}
