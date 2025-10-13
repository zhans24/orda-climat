<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {


        $slider = Slider::query()
            ->where('key', 'home_hero')
            ->where('is_active', true)
            ->with(['slides' => fn ($q) => $q->where('is_active', true)->orderBy('position')])
            ->first();

        $popularCategories = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('position')
            ->take(8)
            ->get();

        $page = Page::where('slug', 'home')->firstOrFail();

        return view('pages.home', compact('slider','popularCategories','page'));
    }
}
