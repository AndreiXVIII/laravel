<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        	[
        		'name'		=> 'Автор не известен',
        		'email'		=> 'author_unknown@g.g',
        		//Из Laravel 6 удалены из фреймворка и перемещены в Composer все str_ и array_ помощники. Сейчас используются классы Str::random(16)
        		//'password'	=> bcrypt(str_random(16)),
        		'password'	=> bcrypt(Str::random(16)),
        	],
        	[
        		'name'		=> 'Автор',
        		'email'		=> 'author1@g.g',
        		'password'	=> bcrypt('123123'),
        	],
        ];

        DB::table('users')->insert($data);
    }
}
