<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutcomingProductDetail extends Model
{
    use HasFactory;
    protected $fillable = ['outcoming_product_id', 'product_id', 'quantity'];
}
