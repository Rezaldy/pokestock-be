<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_listing_id',
        'quantity',
        'isFulfilled',
        'fulfilledAt',
        'fulfilledBy',
    ];

    public function product() {
        $this->hasOneThrough(Product::class, ProductListing::class);
    }

    public function productListing() {
        $this->belongsTo(ProductListing::class);
    }

    public function user() {
        $this->belongsTo(User::class);
    }
}
