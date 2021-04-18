<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    public const TYPES = [
        'single'                => 0,
        'etb'                   => 1,
        'tin'                   => 2,
        'boosterbox'            => 3,
        'miscbox'               => 4,
        'groupbreak'            => 5,
        'jpboosterbox'    => 6,
        'shipping'              => 99,
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
        return $this
            ->hasMany(ProductListing::class)
            ->orderBy('amount');
    }

    /**
     * Get orders of this product
     */
    public function productOrders()
    {
        return $this->hasManyThrough(Order::class, ProductListing::class);
    }
}
