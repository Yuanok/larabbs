<?php
namespace App\Models\Traits;

use Carbon\Carbon;
use Redis;

Trait LastActivedAtHelper{

    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt(){
        //哈希表名
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
        //值域名
        $field = $this->getHashField();
        //最后用户登录的时间
        $now = Carbon::now()->toDateTimeString();
        //用redis存储
        Redis::hSet($hash,$field,$now);
    }

    public function syncUserActivedAt(){

        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
    
        $users_data = Redis::hGetAll($hash);
        foreach($users_data as $user_id => $actived_at){
            $user_id = str_replace($this->field_prefix,'',$user_id);
            if($user = $this->find($user_id)){
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        //写入数据库,删除Redis上的数据
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value){
        //获取今日日期
        //获取哈希表名
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
        //拼接需要获取字段值的字段名
        $field = $this->getHashField();
        //优先获取redis的记录，没有再使用数据库的数据
        $data = Redis::hGet($hash,$field)?:$value;
        if($data){
            return new Carbon($data);
        }else{
            return $this->created_at;
        }      
    }

    public function getHashFromDateString($date){
        return $this->hash_prefix.$date;
    }

    public function getHashField(){
        return $this->field_prefix.$this->id;
    }
}