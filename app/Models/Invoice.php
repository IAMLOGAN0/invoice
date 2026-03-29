<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'shop_id',
        'customer_id',
        'invoice_date',
        'apply_gst',
        'subtotal',
        'cgst',
        'sgst',
        'igst',
        'grand_total',
        'coupon_id',
        'discount_type',
        'discount_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'payment_method',
    ];

    protected $casts = [
        'apply_gst' => 'boolean',
        'discount_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
