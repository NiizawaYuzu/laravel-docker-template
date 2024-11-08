<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{   
    private $todo; // 追記

    public function __construct(Todo $todo)
    {
        $this->todo = $todo; // 追記
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

    public function store(Request $request) 
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

}
