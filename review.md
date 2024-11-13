# Laravel Lesson レビュー①

## Todo一覧機能

### Todoモデルのallメソッドで実行しているSQLは何か
```php
public function index()
    {
        $todo = new Todo();
        $todos = $todo->all();

        return view('todo.index', ['todos' => $todos]);
    }
```
- all()で実行しているSQLは、todosテーブルのレコードを全件取得するためのものです。

### Todoモデルのallメソッドの返り値は何か
- 返り値はCollectionクラスのインスタンスです。
これは、Laravelで用意されている配列操作に特化したクラスです。

### 配列の代わりにCollectionクラスを使用するメリットは
- Collectionクラスは、多様なデータ操作メソッドを持っているため、配列よりもデータの操作を効率よくできることがメリットです。
Collectionのメソッドには、、データのフィルタリングを行うfilter、要素の並び替えを行うsortなどがあります。

### view関数の第1・第2引数の指定と何をしているか
```php
view('todo.index', ['todos' => $todos]);
```
- 第一引数は「'フォルダ名.ファイル名'」を指定していて、第二引数の連想配列には、[blade内での変数名 =>代入したい値 ]が入ります。

### index.blade.phpの$todos・$todoに代入されているものは何か
- $todosに代入されているものは、Collectionインスタンスです。
- $todoに代入されているものは、TODOモデルのインスタンスです。

## Todo作成機能

### Requestクラスのallメソッドは何をしているか
- Requestクラスのallメソッドを使用することでフォームから送信された入力欄を連想配列で全件取得している。

### fillメソッドは何をしているか
- fillメソッドは、引数に指定した連想配列を一括代入している。

```php
public function store(Request $request) 
    {
        $inputs = $request->all(); // 変更
        dd($inputs);

        $todo = new Todo();
        $todo->fill($inputs);
        $todo->save();  

        return redirect()->route('todo.index'); 
    }
```
上記のコードでは、fillメソッドの引数は`$request->all()`という連想配列を格納している変数`$inputs`が入っている。

### $fillableは何のために設定しているか
- $fillableは、一括代入の脆弱性を対策するために代入できる項目に制限を設定している。

### saveメソッドで実行しているSQLは何か
- todoインスタンスのsaveメソッドを実行して、オブジェクトの状態をDBへ保存するINSERT文を実行している

### redirect()->route()は何をしているか
- `redirect()->route('todo.index')`は、ToDoが新規作成されたあと一覧画面にリダイレクトする処理をしている。


## その他

### テーブル構成をマイグレーションファイルで管理するメリット
- マイグレーションファイルで管理するメリットは、2点あります。
一つは、Gitで共有することで開発者同士でテーブル構成を統一することができるところです。
もう一つは、SQLの知識がなくともPHPコードでテーブル操作ができることです。

### マイグレーションファイルのup()、down()は何のコマンドを実行した時に呼び出されるのか
- artisanコマンドでマイグレーションを実行したときに呼び出されます。

### Seederクラスの役割は何か
- 作成したテーブルにテストデータを入れる役割です。テストデータを入れる前に、元々テーブルに存在していたデータは削除する必要があります。

### route関数の引数・返り値・使用するメリット
```php
<a class="btn btn-success" href="{{ route('todo.create') }}">ToDoを追加</a>
```
- root関数の引数はweb.phpファイルで定義した名前付きルートです。
route('ルート名')とすることでルート名からそのルートで設定したURLを生成することができます。（上記の場合todo.createがルート名です。）

- `route('todo.create')`を実行したときの返り値は`http://localhost:8080/todo/create`というURLです。

- root関数を使うメリットは、直接URLを記述するより簡潔なため可読性が向上することと、
URLを変更する際にweb.phpの記述のみ修正すればよいため保守性も高くなります。

### @extends・@section・@yieldの関係性とbladeを分割するメリット
- 一覧画面と新規作成画面のBladeに共通の記述がある場合、Bladeの分割と継承を行い共通部分を新たに別ファイルを作成しまとめます。
共通部分を削除した一覧画面（子Blade）と新規作成画面（子Blade）に、`@extends(継承する親のディレクトリ名.ファイル名)`で継承する親の指定をし、子Bladeの`@section～@endsection`で囲われた部分を親Bladeの@yield()の部分に挿入する。

- Bladeを分割するメリットは重複するコードをまとめることで、その部分に修正が必要な際にまとめたファイルのみ修正すればよいため保守性が高くなる。

### @csrfは何のための記述か
- CSRF対策のトークンを自動で生成・検証するための記述です。

### {{ }}とは何の省略系か
- xss対策のhtmlspecialchars関数の省略形です。
この記述をすることにより、エスケープ処理が行われ悪意のあるコードが実行されるのを防ぎます。
