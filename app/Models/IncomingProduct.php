<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IncomingProductDetail;

class IncomingProduct extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'coming_at'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function incomingProductDetails()
    {
        return $this->hasMany(IncomingProductDetail::class);
    }

    public function details()
    {
        return $this->incomingProductDetails();
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, IncomingProductDetail::class);
    }
}
