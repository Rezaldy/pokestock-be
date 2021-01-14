<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'id',
        'amount',
        'isDiscount',
    ];

    public function product() {
        $this->belongsTo(Product::class);
    }

    public function orders() {
        $this->hasMany(Order::class);
    }
}
