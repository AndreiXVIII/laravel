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