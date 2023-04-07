<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table = 'client_balance';
    use HasFactory;

    protected $fillable = [
        'balance',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
