<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Les plantes associées à ce tag.
     */
    public function plants()
    {
        return $this->belongsToMany(Plant::class);
    }
}
