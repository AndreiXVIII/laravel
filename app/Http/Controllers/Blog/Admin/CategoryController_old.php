<?php

namespace App\Http\Controllers\Blog\Admin;

//Так как мы в одной папке, то подключение снизу не надо. Увидит
//use App\Http\Controllers\Blog\Admin\BaseController;

//Прописываем для Валидации
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $paginator = BlogCategory::paginate(15);

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Показывает какой это метод. Помогает отлавливать ошибки в большрм проэкте
        //dd(__METHOD__);

        //он пока не существует, его создали, но в БД его нет. Поэтому во вьюхе он попадает в else. Так как $item->exists будет равно false
        //Завели пустой item исключительно для того, чтобы могли обращаться к полям {{ $item->title }} во вьюхе item_edit_main_col.blade.php
        $item = new BlogCategory();
        $categoryList = BlogCategory::all();

        //Но лучше делать две разные view
        return view('blog.admin.categories.edit', compact('item', 'categoryList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    //public function store(Request $request)
    public function store(BlogCategoryCreateRequest $request)
    {

        $data = $request->input();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        //Создаст объект, но не добавит в БД
        //1-й способ создания
        //$item = new BlogCategory($data);
        //$item->save();

        //2-й способ создания
        $item = (new BlogCategory())->create($data);

        if($item) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param BlogCategoryRepository $categoryRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, BlogCategoryRepository $categoryRepository)
    {
        $item = BlogCategory::find($id);
        //Получаем данные для отобрежения списка в select при редактировании
        $categoryList = BlogCategory::all();

        //Вместо моделей используем Репозиторий
        $item = $categoryRepository->getEdit($id);
        if(empty($item)) {
            abort(404);
        }

        $categoryList = $categoryRepository->getForComboBox();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    //Из-за валидации меняем Request на BlogCategoryUpdateRequest
    //public function update(Request $request, $id)
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = BlogCategory::find($id);
        if(empty($item)) {
            // редиректит на предыдущий шаг
            return back()
                // В сессию кладем сообщение
                ->withErrors(['msg' => 'Запись id=[{$id}] не найдена'])
                //Возвращаем данные в тот инпут, где была ошибка
                ->withInput();
        }

        //Получаем массив данных, который пришел с request
        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        //fill Пробежится по массиву, и по ключу будет заполнять аттрибуты, которые разрешены по $fillable. Чтобы работал, заполнили модель BlogCategory. Должно быть свойство $fillable
        $result = $item
            ->fill($data)
            ->save();

        //альтернативный вариант вместо fill
        //$result = $item->update($data);

        if($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'ошибка сохранения'])
                ->withInput();
        }



    }

}
