<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Book;
use Illuminate\Http\Request;


/**
* 本のダッシュボード表示(books.blade.php)
*/
Route::get('/', function () {
    $books = Book::orderBy('created_at', 'asc')->get();
    return view('books', [
        'books' => $books
    ]);
})->middleware(['auth'])->name('home');

/**
* 新「本」を追加 
*/
Route::post('/books', function (Request $request) {

    //バリデーション
    $validator = Validator::make($request->all(), [
        'item_name' => 'required|max:255',
    ]);

    //バリデーション:エラー 
    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }
    //以下に登録処理を記述（Eloquentモデル）

  // Eloquentモデル
  $books = new Book;
  $books->item_name = $request->item_name;
  $books->item_number = '1';
  $books->item_amount = '1000';
  $books->published = '2017-03-07 00:00:00';
  $books->save(); 
  return redirect('/');

});

/**
* 本を削除 
*/

Route::delete('/book/{book}', function (Book $book) {
    $book->delete();       //追加
    return redirect('/');  //追加
});

/**
* 「ログイン機能」インストールで追加されています 
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
