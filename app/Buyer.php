<?php

namespace App;

use App\Tramsaction;

//use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    public function transactions()
    {
        return $this->hasMany(Tramsaction::class);
    }
}
