<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'gstin',
        'address',
        'state_code',
        'user_id', // Add user_id
        'shop_id', // Add shop_id
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
