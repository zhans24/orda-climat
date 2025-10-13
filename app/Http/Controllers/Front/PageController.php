<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::with('media')->where('slug', $slug)->firstOrFail();
        abort_unless($page->is_published, 404);

        $view = view()->exists("pages.templates.{$page->template}")
            ? "pages.templates.{$page->template}"
            : "pages.{$page->template}";

        if (! view()->exists($view)) {
            abort(404);
        }

        return view($view, compact('page'));
    }
}
