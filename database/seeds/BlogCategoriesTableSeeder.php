<?php

use Illuminate\Database\Seeder;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [];

        $cName = 'Без категории';
        $categories[] = [
        	'title' => $cName,
        	//Устарело с 6 версии
        	//'slug' => str_slug($cName),
        	'slug' => Str::slug($cName),
        	'parent_id' => 0,
        ];

        for ($i = 1; $i <= 10; $i++) {
        	$cName = 'Категория #'.$i;
        	$parentId = ($i > 4) ? rand(1, 4) : 1;

        	$categories[] = [
        		'title' => $cName,
        		//Устарело
        		//'slug' => str_slug($cName),
        		'slug' => Str::slug($cName),
        		'parent_id' => $parentId,
        	];	
        }

        \DB::table('blog_categories')->insert($categories);

    }
}
