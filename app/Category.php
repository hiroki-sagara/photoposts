<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    public function posts() 
    {
        return $this->hasMany(Post::class);
    }
    
    // カテゴリに属するpostsの件数取得
    public function loadRelationshipCounts()
    {
        $this->loadCount(['posts']);
    }
}
