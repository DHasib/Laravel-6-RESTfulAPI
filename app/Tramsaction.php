<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tramsaction extends Model
{
    protected $fillable = [
         'quentity', 'buyer_id', 'product_id',   
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
    public function products()
    {
        return $this->belongsTo (Product::class);
    }
}
