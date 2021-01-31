<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'totalPrice',
        'paymentReference',
        'includesBulk',
        'includesCodes',
        'bulkSpecifics',
        'futurePackRequest',
    ];

    public function orderLine() {
        return $this->hasMany(OrderLine::class);
    }

    public function productListings() {
        return $this->hasManyThrough(ProductListing::class,OrderLine::class);
    }

    public function customer() {
        return $this->belongsTo(User::class);
    }
}
