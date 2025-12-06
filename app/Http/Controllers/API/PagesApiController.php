<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PagesApiController extends Controller
{
    public function index()
    {
        $pages = Page::where('is_visible', 1)->get();

        return apiResponse(true,'All Pages',$pages,200);
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return apiResponse(true,$page->name . ' Page',$page,200);
    }
}
