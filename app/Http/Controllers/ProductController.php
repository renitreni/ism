<?php

namespace App\Http\Controllers;

use App\Supply;
use App\Gallery;
use App\Product;
use App\Category;
use App\ProductDetail;
use App\PurchaseInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $category = Category::all()->pluck('name');

        return view('product', compact('category'));
    }

    public function table()
    {
        $products = Product::query()
            ->with(['tags'])
            ->selectRaw('products.*, users.name as username')
            ->leftJoin('users', 'users.id', '=', 'products.assigned_to');

        return DataTables::of($products)->setTransformer(function ($data) {
            $data         = collect($data)->toArray();
            $data['name'] = isset($data['tags'][0])
                ? "<label class='badge badge-info'>{$data['tags'][0]['name']['en']}</label> {$data['name']}"
                : $data['name'];

            return $data;
        })->make(true);
    }

    public function create()
    {
        $product = collect([
            "manual_id"     => "",
            "name"          => "",
            "code"          => "",
            "category"      => "",
            "manufacturer"  => "",
            "unit"          => "",
            "description"   => "",
            "assigned_to"   => "",
            "batch"         => "",
            "color"         => "",
            "size"          => "",
            "weight"        => "",
            "type"          => "limited",
            "selling_price" => "0",
            "vendor_price"  => "0",
        ]);

        $gallery = collect([]);

        return view('product_form', compact('product', 'gallery'));
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->with(['tags'])->get()[0];
        $gallery = Gallery::query()->where('product_id', $id)->get();

        return view('product_form', compact('product', 'gallery'));
    }

    public function findProduct(Request $request)
    {
        $product = DB::table('products')
            ->selectRaw('products.*, supplies.quantity, supplies.product_id')
            ->join('supplies', 'supplies.product_id', '=', 'products.id')
            ->where('products.id', $request->product_id);

        return collect($product->get()[0]);
    }

    public function getList(Request $request)
    {
        $product = Product::query()
            ->selectRaw("id as id, name as text")
            ->whereRaw("upper(name) like '%" . strtoupper($request->term) . "%'");

        if ($request->category != '') {
            $product->where('category', $request->category);
        }

        return [
            "results" => $product->get(),
        ];
    }

    public function getSOList(Request $request)
    {
        $product = Product::query()
            ->selectRaw("products.id, products.name as text")
            ->join("product_details", "product_details.product_id", "products.id")
            ->where("product_details.sales_order_id", $request->so_no)
            ->whereRaw("products.name LIKE '%{$request->term}%'");

        if ($request->category != '') {
            $product->where('category', $request->category);
        }

        return [
            "results" => $product->get(),
        ];
    }

    public function store(Request $request)
    {
        $data                = $request->except('fast_moving', 'tags');
        $data['assigned_to'] = auth()->id();
        $id                  = Product::query()->insertGetId($data);

        (new Product())->fastMoving($request, $id);
        Supply::query()->insert([
            "product_id"  => $id,
            "quantity"    => 0,
            "unit_cost"   => 0,
            "assigned_to" => auth()->id(),
        ]);

        return ['success' => true, 'id' => $id];
    }

    public function update(Request $request)
    {
        (new Product())->fastMoving($request, $request->id);

        $product = Product::find($request->id);
        $product->manual_id = $request->get('manual_id');
        $product->name = $request->get('name');
        $product->code = $request->get('code');
        $product->category = $request->get('category');
        $product->manufacturer = $request->get('manufacturer');
        $product->selling_price = $request->get('selling_price');
        $product->vendor_price = $request->get('vendor_price');
        $product->unit = $request->get('unit');
        $product->description = $request->get('description');
        $product->batch = $request->get('batch');
        $product->color = $request->get('color');
        $product->size = $request->get('size');
        $product->weight = $request->get('weight');
        $product->type = $request->get('type');
        $product->assigned_to = $request->get('assigned_to');

        if ($product->isDirty('vendor_price')) {
            $productDetailsIds = (new PurchaseInfo())
                ->pricesAffected($request->id);
            foreach ($productDetailsIds as $id) {
                $productDetail = ProductDetail::find($id);
                $productDetail->selling_price = $request->get('selling_price');
                $productDetail->vendor_price = $request->get('vendor_price');
                $productDetail->save();
            }
        }

        $product->save();

        return ['success' => true];
    }

    public function imageUpload(Request $request)
    {
        if (count($request->file()) > 0) {
            if (Gallery::query()->where('product_id', $request->id)->count()) {
                $gallery = Gallery::query()->where('product_id', $request->id)->get()[0];
                Storage::delete($gallery->path);
                Gallery::query()->where('product_id', $request->id)->delete();
            }

            $path = $request->image->store('images');

            $gallery             = new Gallery();
            $gallery->product_id = $request->id;
            $gallery->name       = $path;
            $gallery->path       = $path;
            $gallery->extension  = $request->image->extension();
            $gallery->size       = $request->image->getSize();
            $gallery->created_by = auth()->id();
            $gallery->save();
        }

        return ['success' => true];
    }

    public function destroy(Request $request)
    {
        Product::query()->where('id', $request->id)->delete();
        Supply::query()->where('product_id', $request->id)->delete();

        return ['success' => true];
    }
}
