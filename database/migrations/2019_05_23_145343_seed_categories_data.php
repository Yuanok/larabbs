<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            [
                'name' => '分享',
                'description' => '分享创造，分享发现',
            ],
            [
                'name' => '教程',
                'description' => '开发技巧、推荐扩展包等',
            ],
            [
                'name' => '问答',
                'description' => '互帮互组，请友善和谐',
            ],
            [
                'name' => '公告',
                'description' => '站点公告',
            ],
        ];

        DB::table('categories')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
