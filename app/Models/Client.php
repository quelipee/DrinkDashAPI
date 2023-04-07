<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'client';
    use HasFactory;
    protected $fillable = [
        'name',
        'address_delivery',
        'address_billing',
        'email',
        'phone_number',
        'user_id'
    ];

    /*
     *
     * relations one for one
     *
     * */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
