<?php

namespace App\Models;

use App\Models\Traits\HasChildren;
use App\Models\Traits\HasOrder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasChildren, HasOrder;

    protected $fillable = [
        'name', 'order', 'slug'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
