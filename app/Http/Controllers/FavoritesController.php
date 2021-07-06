<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    // お気に入りするアクション
    public function store($id)
    {
        // 認証済みユーザ（閲覧者）が、お気に入りする
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    // お気に入りを外すアクション
    public function destroy($id)
    {
        // 認証済みユーザ（閲覧者）が、 お気に入りを外す
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
