1. Установка php


2. Установка composer


3. Установка laravel глобально/локально




4. Ставим debugbar (https://github.com/barryvdh/laravel-debugbar)
Для этого в корне проекта, к примеру: прописываем в терминале:  D:\OS\OSPanel\domains\laravel.loc>

composer require barryvdh/laravel-debugbar --dev



5. Создаем БД
Миграции - для создания таблиц
Модели - чтобы связать фреймворк с базой

php artisan make:model Models/BlogCategory -m
php artisan make:model Models/BlogPost -m

Сначала категории а затем элемент. Чтобы была привязка, и элемент зависил от категории

В миграции:
public function up() - когда вызываем миграцию
public function down() - когда хотим откатиться

В БД пока ничего нет.



6. Выполняем миграции
php artisan migrate

Если ошибка, то
use Illuminate\Support\Facades\Schema
А в функцию boot() дописать Schema::defaultStringLength(191);
В случае ошибки, все равно какие-то таблицы создадуться. И когда повторно выполняем migrate, будет высвечиваться ошибка. Чтобы этого не было, в БД дропаем все таблицы



7. Заполнение структуры таблиц.
В функции up() в миграциях.

Для обновления структуры таблиц(данные пропадут):
php artisan migrate:refresh

Или удалить все таблицы, а при миграции заново создадутся



8. Заполняем БД тестовыми данными. Для этого есть сиды и фабрики
8.1. Seeds (сиды)
php artisan make:seeder UsersTableSeeder
php artisan make:seeder BlogCategoriesTableSeeder

Сиды хранятся в папке database seeds

DatabaseSeeder - главный файл, который управляет сидами и фабриками

8.2. Фабрики
Сделал фабрику BlogPostFactory на основе стандартной фабрики для заполнения тестовыми данными. Создал вручную новый файл и написал код.



10. Запуск сидов:
php artisan db:seed - запускает метод run() в DatabaseSeeder
    $this->call(UsersTableSeeder::class);
    $this->call(BlogCategoriesSeeder::class);
    factory(\App\Models\BlogPost::class, 100)->create();

Примеры:
php artisan db:seed --class=UsersTableSeeder		Для запуска одного сида
php artisan migrate:refresh --seed 					Обновить БД и запустить сиды



11. Создаем REST-контроллер
php artisan make:controller RestTestController --resource

--resource Чтобы появились rest - набор функций по create, update, edit, delete ...


Чтобы система знала, как попасть в контроллер, создаем маршруты. Чтобы связать url с Контроллером.

routes/api - Laravel предлагает базовую структуру по хранению данных, здесь маршруты api
Но, мы отходим, как к примеру с Моделями

php artisan route:list 		- список всех роутов. С помощью него можно проверить, что при создании маршрутов, недопустили ошибку



12. Контроллеры приложения.
Базовый (родительский) контроллер блога
php artisan make:controller Blog/BaseController
Базовый делаем абстрактным, чтобы было понимание того, сущность этого модуля создавать нельзя, от него мы будем только наследоваться

Контроллер статей блога
php artisan make:controller Blog/PostController --resource
Будет наследоваться уже не от Controller, а от BaseController



13. Добавляем верстку на сайт.
Для этого вначале:

Аутентификация. Файлы верстки, базовые, которые предоставляет laravel из коробки
composer require laravel/ui --dev

php artisan ui vue --auth	//eсли vue
php artisan ui react --auth	//eсли react

php artisan migrate
npm install && npm run dev		В powershell пришлось по одтельности вводить, не знает команду &&


В файле views/blog/posts добавили
@extends('layouts.app')
@section('content')
@endsection



14. Контроллер категорий
Создание маршрутов
only - белый список методов, для которых нужно создать маршруты
Route::resource('categories', 'CategoryController')->only($methods)->names('blog.admin.categories');

Создание контроллера:
php artisan make:controller Blog/Admin/CategoryController --resource


php artisan route:list > routes.txt 	- запсиать в текстовый документ список маршрутов

Создаем файл представления
views.blog.admin.category index.blade.php


15. Пагинация



16. Страница редактирования категории
edit


17. Обновление данных
update
$result = $item
    ->fill($data)
    ->save();
Методы лучше переносить на новые строки, чтобы в случае ошибки проще было искать в каком методе она возникула. Laravel укажет номер строки

18. Валидация данных.
В контроллере Controllers\Blog\Admin\CategoryController в методе update()

$rules = [
        'title'         => 'required|min:5|max:200',
        'slug'          => 'max:200',
        'description'   => 'string|max:500|min:3',
        'parent_id'     => 'required|integer|exists:blog_categories,id',
];

//1-й способ отвалидировать данные
//$validatedData = $this->validate($request, $rules);

//2-й способ отвалидировать данные
//$validatedData = $request->validate($rules);

//3-й способ отвалидировать данные
$validator = \Validator::make($request->all(), $rules);
$validatedData[] = $validator->passes();
$validatedData[] = $validator->validate();
$validatedData[] = $validator->valid();
$validatedData[] = $validator->failed();
$validatedData[] = $validator->errors();
$validatedData[] = $validator->fails();

Так делать не надо

Правильно. Пользоваться только им. В больших проэктах проще будет отслеживать
Делаем свой объект request
php artisan make:request BlogCategoryUpdateRequest
создаем надостройку над Request для метода Blog Category Update
создаст по адресу app\Http\Requests



19. Добавление категории (insert, create)
Обновляем все тестовые категории до начального состояния

php artisan migrate:refresh --seed

В edit.blade.php заменили это:
<!-- такого адреса в POST нету, прописанного в action. Но в форму не можем прописать PATCH, поэтому прописываем директиву отдельно -->
<form method="POST" action="{{ route('blog.admin.categories.update', $item->id)  }}">
@method('PATCH')


на это:
@if($item->exists)
<!-- такого адреса в POST нету, прописанного в action. Но в форму не можем прописать PATCH, поэтому прописываем директиву отдельно -->
    <form method="POST" action="{{ route('blog.admin.categories.update', $item->id)  }}">
    @method('PATCH')
@else
    <form method="POST" action="{{ route('blog.admin.categories.store')  }}">
@endif


 создаем BlogCategoryCreateRequest
 php artisan make:request BlogCategoryCreateRequest
 Правила будут одинаковыми как в Update

 Str::slug() не видел в контроллере CategoryController. Поэтому его импортировал use Illuminate\Support\Str;



20. Хранилище (Repository)
В класическом виде его исользуют для того, чтобы абстрогироваться от модели
Будет храниться в папке app. Мы расширяем, но не улучшаем класическую структуру Latavel
Репозиторий - это как обертка для модели.
Абстрогируемся, если понимаем, что у нас может быть замена сущности. Например, замена mysql. Для этого абстрогируемся и делаем интерфейс, чтобы все методы были реализованы.
Абстракция говорит о том, что ты будешь писать больше кода, всегда


!!!!!
Контроллер не должен содержать в себе логики, контроллер должен обоащаться к тому, кто содержит эту логику!
Контроллеры должны быть маленькими
Модели должны быть маленькими
Вся логика должна быть вынесена в классы
Все классы должны быть маленькими
!!!!!


21. Переносим логику в репозиторий
Класс CategoryController переименовали в old, и создали новый класс CategoryController для кода репозитория




