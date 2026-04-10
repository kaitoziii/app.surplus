<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',       // siapa pemilik
        'product_id',    // produk yang dibeli
        'quantity',      // jumlah yang dibeli
        'status'         // pending / completed / expired
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}