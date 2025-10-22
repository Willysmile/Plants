<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'tag_category_id'];

    /**
     * Les plantes associées à ce tag.
     */
    public function plants()
    {
        return $this->belongsToMany(Plant::class);
    }

    /**
     * La catégorie du tag
     */
    public function tagCategory()
    {
        return $this->belongsTo(TagCategory::class, 'tag_category_id');
    }
}
