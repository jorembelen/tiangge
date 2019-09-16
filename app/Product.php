<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function attributes()
    {
        return $this->hasMany(ProductsAttribute::class, 'product_id');
    }
}
