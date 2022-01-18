<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierCollection;
use App\Models\IncomingProduct;
use App\Models\OutcomingProduct;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function quantity()
    {
        // $a = IncomingProduct::select('incoming_products.coming_at', DB::raw('COUNT(*) AS total'), DB::raw('MONTH(incoming_products.coming_at) AS month'))
        //     ->join('suppliers', 'suppliers.id', '=', 'incoming_products.supplier_id')
        //     ->join('incoming_product_details', 'incoming_product_details.incoming_product_id', '=', 'incoming_product_details.id')
        //     ->join('products', 'products.id', '=', 'incoming_product_details.product_id')
        //     ->groupBy('month')
        //     ->get();

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
        $suppliers = Supplier::all();

        return new SupplierCollection($suppliers);
        // $response = [];
        // foreach($suppliers as $supplier) {
        //     $products = $supplier->products;
        //     $item = [
        //         'id' => $supplier->id,
        //         'name' => $supplier->name,
        //     ];
        // }
    }
}
