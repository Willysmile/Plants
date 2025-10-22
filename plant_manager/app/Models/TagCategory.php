<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
    protected $table = 'tag_categories';

    public function tags()
    {
        return $this->hasMany(Tag::class, 'category_id');
    }
}
