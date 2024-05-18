<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'price',
        'image',
        'discounted_price',
        'description'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }
    
    public function productCategories()
    {
        return $this->hasMany(Category::class,'product_id');
    }

    public function scopeIsActive($query, $status = true)
    {
        return $query->where('is_active', $status);
    }

}
