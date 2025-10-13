<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug',$slug)->where('is_active',true)->with(['media','category'])->firstOrFail();

        $page = (object)[
            'title' => $product->seo_title ?? $product->name,
            'meta_title' => $product->seo_title ?? $product->name,
            'meta_description' => $product->seo_description ?? null,
        ];

        return view('pages.product', compact('product','page'));
    }
}
