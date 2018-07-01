<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //暂时关闭安全保护
        Model::unguard();

        $this->call(UsersTableSeeder::class);

        //填充完毕后重新打开保护
        Model::reguard();
    }
}
