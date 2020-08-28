<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            //Автоинкремент
            $table->increments('id');
            //Чтобы поле было от нуля и выше(unsigned), по умолчанию значение 0. Для того, чтобы быть как автоинкремент. Когда создаем категорию, у нее паррента по умолчанию нет  
            $table->integer('parent_id')->unsigned()->default(1);

            //Создаем слаг - название категории 'title' в транслите. Должен быть уникальным(unique). На базе него строим url
            $table->string('slug')->unique();
            //Название категории
            $table->string('title');
            //Описание. По умолчанию его можно не заполнять(nullable). А выше поля обязательные
            $table->text('description')->nullable();

            //Когда была создана/изменена
            $table->timestamps();
            //Когда запись была удалена
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_categories');
    }
}
