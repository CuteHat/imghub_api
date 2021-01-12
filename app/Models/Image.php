<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'url',
        'view_count',
        'user_id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'image_tags', 'image_id', 'tag_id');
    }
}
