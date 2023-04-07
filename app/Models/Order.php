<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    use HasFactory;

    protected $fillable = [
        'date_order',
        'status_order',
        'client_id'
    ];

    public function orders_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
