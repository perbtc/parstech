<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'code', 'category_id', 'brand_id',
        'image', 'gallery', 'video', 'short_desc', 'description',
        'stock', 'min_stock', 'unit', 'barcode', 'is_active'
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
