<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidder extends Model
{
    use HasFactory;

    public function bids(){
        return $this->hasMany(Bid::class);
    }
}
