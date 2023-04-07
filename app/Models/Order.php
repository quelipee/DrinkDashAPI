<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'order';
    use HasFactory;

    protected $fillable = [
        'date_order',
        'status_order',
        'client_id'
    ];

    public function orders_items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
