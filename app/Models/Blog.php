<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'content', 'image', 'status', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
