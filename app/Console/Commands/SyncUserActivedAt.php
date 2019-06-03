<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SyncUserActivedAt extends Command{
    protected $signature = 'larabbs:sync_user_actived_at'; 
    protected $description = '将用户最后登录时间记录从Redis写入到数据库中';

    public function handle(User $user){
        $user->syncUserActivedAt();
        $this->info('同步成功');
    } 
}