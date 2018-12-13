<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Comment;

class CommentsController extends Controller
{
  public function index()
  {
    $comments = (new Comment())->get();
    $params = [
      'comments' => $comments
    ];
    return view('admin.comments', $params);
  }

  public function acceptComment($id)
  {

    \DB::table('comments')->where('id', $id)->update(['status' => true]);
    return back();
  }

  public function addComment(Request $request)
  {
    $comment = $request->input('comment');
    $article_id = (int)$request->input('article_id');
    $user_id = auth()->user()->id;

    $objComment = new Comment();
    $comments = $objComment->create([
      'article_id' => $article_id,
      'user_id'    => $user_id,
      'comment'    => $comment
    ]);

    if($objComment) {

      return redirect()->route('show_article', ['comments' => $comments])->with('success', 'Комментарий успешно добавлен');
    }

    return back()->with('error' , 'Не удалось добавить комментарий');

  }
}
