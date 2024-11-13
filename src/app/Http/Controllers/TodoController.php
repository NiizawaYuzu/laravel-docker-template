<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Todo;
// use Illuminate\Http\Request;

class TodoController extends Controller
{   
    private $todo; // 空のプロパティ todoモデルを使いまわしたい

    public function __construct(Todo $todo)
    {
        $this->todo = $todo; 
    }
    
    public function index()
    {
        // $todo = new Todo();
        // $todos = $todo->all();
        $todos = $this->todo->all();

        return view('todo.index', ['todos' => $todos]);
    }

    public function create()
    {
        return view('todo.create'); // 追記
    }

    public function store(TodoRequest $request) 
    {
        $inputs = $request->all(); // 変更

        // $todo = new Todo();
        // $todo->fill($inputs);
        $this->todo->fill($inputs);
        $this->todo->save(); 

        return redirect()->route('todo.index'); 
    }

    public function show($id)
    {
        $todo = $this->todo->find($id);
        return view('todo.show', ['todo' => $todo]);
    }

       
    public function edit($id)
    {
        // TODO: 編集対象のレコードの情報を持つTodoモデルのインスタンスを取得
        $todo = $this->todo->find($id);
        return view('todo.edit', ['todo' => $todo]);

    }

    public function update(TodoRequest $request, $id) // 第1引数: リクエスト情報の取得　第2引数: ルートパラメータの取得
    {
        // TODO: リクエストされた値を取得
        $inputs = $request->all();//
        $todo = $this->todo->find($id);//$todoの内容を再度定義
        $todo->fill($inputs)->save();

        return redirect()->route('todo.show', $todo->id);
    }

    public function delete($id)
    {
        $todo = $this->todo->find($id); // TODO: 削除対象のレコードの情報を持つTodoモデルのインスタンスを取得
        $todo ->delete();

        return redirect()->route('todo.index');
    }

}
