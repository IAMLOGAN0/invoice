<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hsn_code',
        'price',
        'gst_percentage',
        'shop_id', // Add shop_id
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
