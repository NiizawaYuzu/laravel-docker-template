# Laravel Lesson レビュー②

## Todo編集機能

### @method('PUT')を記述した行に何が出力されているか
```php
<form method="POST" action="{{ route('todo.update', $todo->id) }}">
          @csrf
          @method('PUT')
```
 @method('PUT')を記述した行には`<input type="hidden" name="_method" value="PUT">`というinputタグが出力されます。

 上記のような方法でPUTメソッドを指定する理由は、HTMLの仕様として<from method="PUT">のように指定することができないからです。

### findメソッドの引数に指定しているIDは何のIDか
```php
public function edit($id)
    {
        $todo = $this->todo->find($id);
        return view('todo.edit', ['todo' => $todo]);

    }
 ```
- findメソッドの引数に指定しているIDは、ルートパラメータのIDです。

### findメソッドで実行しているSQLは何か
- findメソッドで実行しているSQL文は、編集対象のレコード情報を持つToDoモデルのインスタンスを取得するものです。

### findメソッドで取得できる値は何か
- findメソッドで取得できる値は、指定のidのレコードデータです。

### saveメソッドは何を基準にINSERTとUPDATEを切り替えているのか
新規作成

```php
public function store(TodoRequest $request) 
    {
        $inputs = $request->all(); // 変更

        // $todo = new Todo();
        // $todo->fill($inputs);
        $this->todo->fill($inputs);
        $this->todo->save(); 

        return redirect()->route('todo.index'); 
    }
```
更新機能
```php
public function update(TodoRequest $request, $id) // 第1引数: リクエスト情報の取得　第2引数: ルートパラメータの取得
    {
        // TODO: リクエストされた値を取得
        $inputs = $request->all();//
        $todo = $this->todo->find($id);//$todoの内容を再度定義
        $todo->fill($inputs)->save();

        return redirect()->route('todo.show', $todo->id);
    }
```
saveメソッドは、呼び出している場所が`$this->todo`か、DBから取得してきたデータを代入している`$todo`かを基準に処理を分けています。


## Todo論理削除

### traitとclassの違いとは
traitとclassの違いは2つあります。
- 複数のクラス間でコードの共通化や再利用ができるか否か
- インスタンス化できるか否か

### traitを使用するメリットとは
- traitを使用するメリットは、複数のクラス間でコードを共通化や再利用することが可能なことです。

## その他

### TodoControllerクラスのコンストラクタはどのタイミングで実行されるか
```php
class TodoController extends Controller
{   
    private $todo; // 空のプロパティ

    public function __construct(Todo $todo)
    {
        $this->todo = $todo; 
    }
}
```
`$this->todo`のタイミングでコンストラクタが実行される。
それによってtodoクラスのインスタンスをController内で使いまわせるようになります。

### RequestクラスからFormRequestクラスに変更した理由
 RequestクラスからFormRequestクラスに変更した理由は、FormRequestクラスを継承したTodoRequestクラスを利用することで
 バリデーションを行うことができるからです。
 バリデーションとは、入力値が正しい形式や範囲に合致しているかどうかを検証することで意図せぬ入力によるシステムエラーを防ぎ
 セキュリティが向上します。

### $errorsのhasメソッドの引数・返り値は何か
- $errorsのhasメソッドの引数は`content`（入力欄のname属性）で、その入力欄でバリデーションエラーが発生しているかを判定し、
チェックに引っかかればtrueを返します。

### $errorsのfirstメソッドの引数・返り値は何か
- $errorsのhasメソッドの引数は`content`（入力欄のname属性）で、その入力欄で最初に発生したエラーメッセージを返します。

### フレームワークとは何か
フレームワークとは、Webアプリケーションやシステム開発を行う際に必要となる機能や、基本的な骨組みをまとめたものです。

### MVCはどういったアーキテクチャか
- MVCは開発効率を高めるために作られたアーキテクチャです。
- MNCはModel（DBとのやり取りを司る）、View(ユーザに表示する画面の生成を司る)、Controller(ユーザからの入力に基づきModel、Viewの制御を司る)の頭文字を取っています。

### ORMとは何か、またLaravelが使用しているORMは何か
- ORMはObject Relational Mappingの略で、プログラミング言語のClassとDBのテーブルを関連付けする機能です。
Laravelで使用しているORMはEloquentです。

### composer.json, composer.lockとは何か
- composer.json, composer.lockとは、Laravelにおいてフレームワークやライブラリを管理するシステムである
composerが関連している、ファイル・フォルダのことです。
それがあることで、パッケージの依存関係を考慮せずとも依存ライブラリも自動的にインストールすることが可能になります。

### composerでインストールしたパッケージ（ライブラリ）はどのディレクトリに格納されるのか
- composerでインストールしたパッケージ（ライブラリ）はvendorディレクトリに格納されています。