<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductListing extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'id',
        'amount',
        'isDiscount',
        'hiddenStock',
    ];

    public function product() {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
