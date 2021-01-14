<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const TYPES = [
        'single'        => 0,
        'etb'           => 1,
        'tin'           => 2,
        'boosterbox'    => 3,
        'miscbox'       => 4,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'amount_in_stock',
        'hidden',
        'image',
    ];

    /**
     * Get the listings for a product
     */
    public function productListings()
    {
        return $this->hasMany(ProductListing::class);
    }

    /**
     * Get orders of this product
     */
    public function productOrders()
    {
        return $this->hasManyThrough(Order::class, ProductListing::class);
    }


}
