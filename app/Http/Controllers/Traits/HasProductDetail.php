<?php

namespace App\Http\Controllers\Traits;

use App\ProductDetail;

trait HasProductDetail {
    public function getProductDetail($id)
    {
        return ProductDetail::query()
            ->selectRaw('products.code, products.category, products.type, products.unit, products.manual_id, product_details.*, supplies.quantity')
            ->where('sales_order_id', $id)
            ->join('products', 'products.id', 'product_details.product_id')
            ->join('supplies', 'supplies.product_id', 'product_details.product_id')
            ->orderBy('products.category')
            ->get();

    }
}
