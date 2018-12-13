<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use App\Category;
use App\CategoryArticle;
use App\Http\Requests\ArticleRequest;

use Illuminate\Support\Facades\DB;

class ArticlesController extends Controller
{
  public function index()
  {
//    $objArticle = new Article();
//    $articles = $objArticle->orderBy('id', 'desc')->get();

    $articles = DB::table('articles')->orderBy('id', 'desc')->paginate(2);

    return view('admin.articles.index', ['articles' => $articles]);
  }
  public function showArticle(int $id, $slug)
  {
    $objArticle = Article::find($id);
    if(!$objArticle) {
      return abort(404);
    }
    $comments = $objArticle->comments()->where('status', 1)->paginate(5);


    return view('show_article', ['article' => $objArticle, 'comments' => $comments]);
  }

  public  function addArticle()
  {
    $objCategory = new Category();
    $categories = $objCategory->get();

    return view('admin.articles.add', ['categories' => $categories]);
  }

  public function addRequestArticle(ArticleRequest $request)
  {
    $objArticle = new Article();
    $objCategoryArticle = new CategoryArticle();

    $fullText = $request->input('full_text') ?? null;
    $objArticle= $objArticle->create([
      'title'       => $request->input('title'),
      'short_text'  => $request->input('short_text'),
      'full_text'   => $fullText,
      'author'      => $request->input('author')
    ]);

    if($objArticle) {
      foreach($request->input('categories') as $category_id) {
        $category_id = (int)$category_id;
        $objCategoryArticle = $objCategoryArticle->create([
          'category_id'    => $category_id,
          'article_id'     => $objArticle->id
        ]);
      }

      return redirect()->route('articles')->with('success', 'Статья успешно добавлена');
    }

    return back()->with('error' , 'Не удалось добавить статью');

  }
  public function editArticle(int $id)
  {
    $objCategory = new Category();
    $categories = $objCategory->get();
    $objArticle = Article::find($id);
    if(!$objArticle) {
      return abort(404);
    }

    $mainCategories = $objArticle->categories;
    $arrCategories = [];
    foreach ($mainCategories as $category) {
      $arrCategories[] = $category->id;
    }


    return view('admin.articles.edit', [
      'categories'    => $categories,
      'article'       => $objArticle,
      'arrCategories' => $arrCategories
    ]);
  }

  public function editRequestArticle(int $id, ArticleRequest $request)
  {
    $objArticle = Article::find($id);
    if(!$objArticle) {
      return abort(404);
    }

    $objArticle->title = $request->input('title');
    $objArticle->short_text = $request->input('short_text');
    $objArticle->full_text = $request->input('full_text');
    $objArticle->author = $request->input('author');

    if($objArticle->save()) {
      //Обновляем привязку к категориям
      $objArticleCategory = new CategoryArticle();
      $objArticleCategory->where('article_id', $objArticle->id)->delete();

      $arrCategories = $request->input('categories');

      if(is_array($arrCategories)) {
        foreach ($arrCategories as $category) {
          $objArticleCategory->create([
            'category_id' => $category,
            'article_id'  => $objArticle->id
          ]);
        }
      }

      return redirect()->route('articles')->with('success', 'Статья успешно обновлена');
    }

    return back()->with('error' , 'Не удалось изменить статью');
  }
  public function deleteArticle(Request $request)
  {
    if($request->ajax()) {
      $id = (int)$request->input('id');
      $objArticle = new Article();

      $objArticle->where('id', $id)->delete();

      echo "success";
    }
  }
}
