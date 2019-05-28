<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        $users_ids = User::all()->pluck('id')->toArray();
        $topics_ids = Topic::all()->pluck('id')->toArray();

        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)->times(1000)->make()
                ->each(function ($reply,$index) use ($users_ids,$topics_ids,$faker){
                    $reply->user_id = $faker->randomElement($users_ids);
                    $reply->topic_id = $faker->randomElement($topics_ids);
        });
        Reply::insert($replys->toArray());
    }

}

