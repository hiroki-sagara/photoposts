<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage; // 追加

use App\Category;

class PostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            $posts = $user->feed_posts()->orderBy('created_at', 'desc')->paginate(10);
            
            $categories = Category::pluck('category', 'id');
            
            $data = [
                'user' => $user,
                'posts' => $posts,
                'categories' => $categories,
            ];
            
        }
        
        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    }
    
    public function store(Request $request)
    {
        
        // バリデーション
        $request->validate([
            'datafile' => 'required',
        ]);
    
        
        // ファイルの存在チェック
        if ($request->hasFile('datafile'))
        {
            $disk = Storage::disk('s3');
 
            // S3にファイルを保存し、保存したファイル名を取得する
            $fileName = $disk->put('', $request->file('datafile'));
            
            
            //dd($disk->url($fileName));
        }
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->posts()->create([
            'image_url' => $disk->url($fileName),
            // category_id保存
            'category_id' => $request->category_id,
        ]);
        
        return back();
    }
    
    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $post = \App\Post::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $post->user_id) {
            $post->delete();
        }

        // 前のURLへリダイレクトさせる
        return back();
    }
}
