<?php

namespace App\Http\Controllers\Blog\Admin;

//Так как мы в одной папке, то подключение снизу не надо. Увидит
//use App\Http\Controllers\Blog\Admin\BaseController;

use App\Models\BlogCategory;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $paginator = BlogCategory::paginate(5);

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
        dd(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = BlogCategory::find($id);

        //Получаем данные для отобрежения списка в select при редактировании
        $categoryList = BlogCategory::all();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
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
        //fill Пробежится по массиву, и по ключу будет менять. Чтобы работал, заполнили модель BlogCategory. Должно быть свойство $fillable
        $result = $item
            ->fill($data)
            ->save();

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
