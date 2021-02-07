<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_id',
        'totalPrice',
        'paymentReference',
        'includesBulk',
        'includesCodes',
        'bulkSpecifics',
        'futurePackRequest',
    ];

    public function orderLines() {
        return $this->hasMany(OrderLine::class)->with('productListing');
    }

    public function customer() {
        return $this->belongsTo(User::class);
    }
}
