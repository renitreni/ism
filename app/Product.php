<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Product extends Model
{
    use SoftDeletes;
    use HasTags;

    protected $guarded = ['id'];

    public static function isLimited($product_id)
    {
        $count = self::query()
            ->where('type', 'limited')
            ->where('id', $product_id)
            ->get()
            ->count();

        return $count == 1 ? true : false;
    }

    public static function isUnLimited($product_id)
    {
        $count = self::query()
            ->where('type', 'unlimited')
            ->where('id', $product_id)
            ->get()
            ->count();

        return $count == 0 ? true : false;
    }

    public function results()
    {
        return $this::withAnyTags(['Fast Moving'])
            ->selectRaw('supplies.*, products.name as product_name, products.code')
            ->join('supplies', 'products.id', '=', 'supplies.product_id');
    }

    public function fastMoving($request, $id)
    {
        $product = $this->find($id);
        if ($request->fast_moving) {
            $product->attachTag('Fast Moving');
        } else {
            $product->detachTag('Fast Moving');
        }
    }
}
