<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'blog_id', 'like'];

    // like link to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
