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
        'requiresBulk',
        'bulkSpecifics',
        'futurePackRequest',
    ];

    public function orderLine() {
        $this->hasMany(OrderLine::class);
    }

    public function productListings() {
        $this->hasManyThrough(ProductListing::class,OrderLine::class);
    }

    public function customer() {
        $this->belongsTo(User::class);
    }
}
