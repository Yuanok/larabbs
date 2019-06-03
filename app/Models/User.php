<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContracts;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use App\Models\Topic;
use Auth;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmailContracts
{
    use MustVerifyEmailTrait;
    use HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    use Notifiable{
        notify as protected laravelNotify;
    }

    public function notify($instance){
        //如果通知的人为当前用户就不用通知
        // this是指话题作者
        if($this->id == Auth::id()){
            return;
        }
        ///数据库类型通知才需要提醒
        if(method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics(){
        return $this->hasMany(Topic::class);
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function isAuthorOf($model){
        return $this->id == $model->user_id;
    }

    public function markAsRead(){
        $this->notification_count =0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
    
    public function setPasswordAttribute($value){
        if(strlen($value) != 60 ){
            //密码长度不等于60，密码没有哈希加密
              $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path){
        if(!starts_with($path,'http')){
           $path = config('app.url')."/uploads/images/avatar/".$path; 
        }
        $this->attributes['avatar'] = $path;
    }
}
