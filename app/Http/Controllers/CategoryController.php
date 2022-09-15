<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function destroy(Request $request)
    {
        Category::truncate();
        foreach ($request->category as $value) {
            Category::query()->insert(['name' => strtoupper($value), 'type' => 'SERVICES']);
        }
        $category = Category::all()->pluck('name');

        return ['success' => true, 'categories' => $category];
    }

    public function store(Request $request)
    {
        Category::query()->create(['name' => strtoupper($request->get('category')), 'type' => 'SERVICES']);
        $category = Category::all()->pluck('name');

        return ['success' => true, 'categories' => $category];
    }

    public function getList(Request $request)
    {
        $category = Category::query()
                           ->selectRaw("id as id, name as text")
                           ->whereRaw("name LIKE '%{$request->term}%'");
        return [
            "results" => $category->get(),
        ];
    }
}
