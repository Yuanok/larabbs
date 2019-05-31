<?php
namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

trait ActiveUserHelper{
    //用户临时存在数据
    protected $users = [];
    //设置权重
    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $pass_days = 7;
    protected $users_number = 6;

    //设置缓存配置
    protected $cache_key = 'larabss_active_users';
    protected $cache_expire_in_minutes = 65;

    //因为活跃用户是需要经常读取的数据，所以缓存中有数据先从缓存读取数据，若缓存没有数据就调用闭包返回需要的数据并设置缓存。
    public function getActiveUsers(){
       // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key,$this->cache_expire_in_minutes,function(){
            return $this->calculateActiveUsers();
        });
    }

    private function calculateActiveUsers(){
        $this->calculateTopicScore();
        $this->calculateReplyScore();
        //arrya_sort（数组，排序的数据）
        $users = array_sort($this->users,function($user){
            return $user['score'];
        });
        //array_reverse 数据反转（从高到低）
        $users = array_reverse($this->users,true);
        //截取前6位用户
        $users = array_slice($users,0,$this->users_number,true);

        $active_users = collect();

        foreach($users as $user_id=>$user){
            //查找是否存在此用户
            $user = $this->find($user_id);
            //存在则就推进集合中
            if($user){
                $active_users->push($user);
            }
        }

        return $active_users;
    }

    private function calculateTopicScore(){
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
        ->where('created_at','>=',Carbon::now()->subDays($this->pass_days))
        ->groupBy('user_id')
        ->get();

        //计算7天内发过帖子的所有用户的分数
        foreach($topic_users as $value){
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    private function calculateReplyScore(){
        $reply_users = Reply::query()->select(DB::raw('user_id,count(*) as reply_count'))
        ->where('created_at','>=',CarBon::now()->subDays($this->pass_days))
        ->groupBy('user_id')->get();

        foreach($reply_users as $value){
            $reply_score = $value->reply_count * $this->reply_weight;
            if(isset($this->users[$value->user_id])){
                $this->users[$value->user_id]['score'] += $reply_score; 
            }else{
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    public function calculateAndCacheActiveUsers(){
        //获取活跃用户
        $active_users =$this->calculateActiveUsers();
        //缓存数据
        $this->cacheActiveUsers($active_users);
    }

    private function cacheActiveUsers($active_users){
        //将数据放入缓存
        Cache::put($this->cache_key,$this->active_users,$cache_expire_in_minutes);
    }

}
