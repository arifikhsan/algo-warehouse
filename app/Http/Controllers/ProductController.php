<?php

namespace App\Http\Controllers;

use App\Models\IncomingProduct;
use App\Models\OutcomingProduct;
use App\Models\Product;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function quantity()
    {
        $months = IncomingProduct::selectRaw('month(coming_at) month_id, monthname(coming_at) month')->groupBy('month', 'month_id')->get();

        foreach ($months as $month) {
            $incomingProduct = IncomingProduct::selectRaw('sum(incoming_product_details.quantity) as incoming_quantity')
                ->whereMonth('incoming_products.coming_at', '=', $month['month_id'])
                ->join('incoming_product_details', 'incoming_product_details.incoming_product_id', '=', 'incoming_products.id')
                ->first();
            $outcomingProduct = OutcomingProduct::selectRaw('sum(outcoming_product_details.quantity) as outcoming_quantity')
                ->whereMonth('outcoming_products.going_at', '=', $month['month_id'])
                ->join('outcoming_product_details', 'outcoming_product_details.outcoming_product_id', '=', 'outcoming_products.id')
                ->first();
            $incoming = (int) $incomingProduct['incoming_quantity'];
            $outcoming = (int) $outcomingProduct['outcoming_quantity'];
            $leftover = $incoming - $outcoming;
            $month['products'] = [
                'incoming' => $incoming,
                'outcoming' => $outcoming,
                'leftover' => $leftover,
            ];
        }

        return response()->json($months);
    }

    public function average()
    {
        $suppliers = Supplier::select('id', 'name as supplier_name')->get();

        foreach ($suppliers as $supplier) {
            $products = Product::selectRaw('avg(incoming_product_details.quantity) as average')
                ->join('incoming_product_details', 'incoming_product_details.product_id', '=', 'products.id')
                ->join('incoming_products', 'incoming_products.id', '=', 'incoming_product_details.incoming_product_id')
                ->join('suppliers', 'incoming_products.supplier_id', '=', 'suppliers.id')
                ->where('suppliers.id', $supplier->id)
                ->first();

            $supplier['quantity_per_product_average'] = (double) $products['average'];
        }

        return response()->json($suppliers);
    }
}
