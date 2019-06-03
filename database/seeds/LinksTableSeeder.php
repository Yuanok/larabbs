<?php

use Illuminate\Database\Seeder;
use App\Models\Link;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //生成六条数据
        $links = factory(Link::class)->times(6)->make();
        //插诶插入数据
        Link::insert($links->toArray());
    }
}
