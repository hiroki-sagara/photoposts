<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * このユーザが所有する投稿。（ Postモデルとの関係を定義）
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
     /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_posts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Post::whereIn('user_id', $userIds);
    }
    
    public function favorites() {
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id')->withTimestamps();
    }
    
    // お気に入りの追加
    public function favorite($postId) {
        
        //　すでにお気に入りかの確認
        $exist = $this->is_favorites($postId);
        // 対象が自分かの確認
        //$its_me = $this->id == $userId;
        
        if ($exist) {
            // すでにお気に入りなら何もしない
            return false;
        } else {
            // お気に入りでなければお気に入りにする
            $this->favorites()->attach($postId);
            return true;
        }
        
    }
        
    //お気に入りの削除
    public function unfavorite($postId) {
        
        // すでにお気に入りかの確認
        $exist = $this->is_favorites($postId);
        // 対象が自分自身かの確認
        //$its_me = $this->id == $userId;

        if ($exist) {
            // すでにお気に入りしていればお気に入りを外す
            $this->favorites()->detach($postId);
            return true;
        } else {
            // 未お気に入りであれば何もしない
            return false;
        }
        
    }
    
    // お気に入りか調べる
    public function is_favorites($postId)
    {
        return $this->favorites()->where('post_id', $postId)->exists();
    }
    
    /**
     * このユーザに関係するモデルの件数をロードする。
     */
     
    public function loadRelationshipCounts()
    {
        $this->loadCount(['posts', 'followings', 'followers', 'favorites']);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
