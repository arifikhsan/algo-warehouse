<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function incomingProducts()
    {
        return $this->hasMany(IncomingProduct::class);
    }

    public function products()
    {
        // return $this->join
        // return $this->hasManyDeep(Product::class,
        // [IncomingProduct::class, IncomingProductDetail::class],
        // ['']);
        $products = [];
        $incoming = $this->incomingProducts->each(function ($incomingProduct) {
            return $incomingProduct->incomingProductDetails->each(function($detail) {
                return $detail->product;
            });
        });
        foreach($incoming as $item) {
            array_push($products, $item['product']);
        }
        return $products;
    }
}
