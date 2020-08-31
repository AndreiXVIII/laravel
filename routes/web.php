<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//namespaces дополняется Blog, так как ищет контреллеры в папке Controller, а у нас еще есть папки Blog, где и лежат нужные контроллеры
//prefix для url, то есть, все они будут начинаться с blog. Например, blog/posts
Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function() {
	Route::resource('posts', 'PostController')->names('blog.posts');
});

//Route::resource('rest', 'RestTestController')->names('restTest');

$groupData = [
	'namespace' => 'Blog\Admin',
	'prefix'	=> 'admin/blog'
];

Route::group($groupData, function() {
	$methods = ['index', 'edit', 'store', 'update', 'create'];
	Route::resource('categories', 'CategoryController')->only($methods)->names('blog.admin.categories');
});