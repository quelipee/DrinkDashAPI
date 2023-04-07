<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'product';
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'img_product',
        'price',
        'category'
    ];

    public function order_item()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
