<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('category_id')->unsigned();
            //Была ошибка с привязкой ключа по полю. В структуре таблицы users тип данных в новой версии laravel bigint
            //$table->integer('user_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->string('slug')->unique();
            $table->string('title');

            //Выдержка статьи, кусочек статьи. Например, дата, автор, и коротко о чем статья
            $table->text('excerpt')->nullable();

            //Два поля отвечающие за саму статью
            //Статья. Автоматически будет превращаться в html
            $table->text('content_raw');
            //В видеhtml
            $table->text('content_html');

            //По умолчанию статьи не опубликованные
            $table->boolean('is_published')->default(false);
            //rjulf cnfnmz ,skf jge,kbrjdfyf
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            //Ключи. Связи полей с другими табличками
            //Поле user_id подключаемся к id из таблицы users
            $table->foreign('user_id')->references('id')->on('users');
            //Поле category_id подключается по полю id к табличке blog_gategories
            $table->foreign('category_id')->references('id')->on('blog_categories');

            //По этому полю будем делать поиск, выборку
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
