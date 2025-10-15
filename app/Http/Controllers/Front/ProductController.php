<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Page;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $breadcrumbs = $product->category
            ? $product->category->breadcrumbs()->map(fn ($c) => [
                'title' => $c->name,
                'url'   => route('category.show', $c->slug),
            ])->push(['title' => $product->name, 'url' => ''])
            : collect([['title' => $product->name, 'url' => '']]);

        $related = Product::query()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->where('is_active', true)
            ->with('media')
            ->inRandomOrder()
            ->take(12)
            ->get();

        $productsPage = Page::query()
            ->where('slug', 'products')
            ->where('is_published', true)
            ->first();

        return view('pages.product', [
            'product'      => $product,
            'breadcrumbs'  => $breadcrumbs,
            'related'      => $related,
            'productsPage' => $productsPage,
            'page'         => (object)[
                'meta_title'       => $product->seo_title,
                'meta_description' => $product->seo_description,
            ],
        ]);
    }
}
