<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $popularCategories = Category::query()
            ->active()
            ->where('is_popular', true)
            ->orderBy('position')
            ->with('media')
            ->withCount('products')
            ->get();

        $page = (object) [
            'title'            => 'Популярные категории',
            'meta_title'       => 'Популярные категории',
            'meta_description' => null,
        ];

        $breadcrumbs = collect([
            ['title' => 'Главная', 'url' => route('home')],
            ['title' => 'Каталог товаров', 'active' => true],
        ]);

        return view('pages.popular', compact('popularCategories', 'page', 'breadcrumbs'));
    }

    public function show(string $slug): View
    {
        $category = Category::query()
            ->active()
            ->where('slug', $slug)
            ->with([
                'children' => fn($q) => $q->active()->ordered(),
            ])
            ->firstOrFail();

        $products = Product::query()
            ->active()
            ->inCategoryTree($category)
            ->with('media')
            ->orderBy('id', 'desc')
            ->paginate(9);

        $breadcrumbs = [
            ['title' => 'Главная', 'url' => route('home')],
            ['title' => 'Каталог товаров', 'url' => route('catalog.index')],
            ['title' => $category->name, 'active' => true],
        ];

        $page = (object)[
            'title' => $category->seo_title,
            'meta_title' => $category->seo_title,
            'meta_description' => $category->seo_description,
        ];

        return view('pages.category', compact('category', 'products', 'breadcrumbs', 'page'));
    }
}
