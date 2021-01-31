<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_listing_id',
        'quantity',
        'amount',
        'isCompleted',
    ];

    public function productListing()
    {
        return $this->belongsto(ProductListing::class)->with('product');
    }
}
