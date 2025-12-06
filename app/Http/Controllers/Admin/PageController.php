<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Page;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Validator;

class PageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Page Show', only: ['index']),
            new Middleware('permission:Page Create', only: ['create','store']),
            new Middleware('permission:Page Edit', only: ['edit','update']),
            new Middleware('permission:Page Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index',compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:pages,name',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $page = Page::create($validator->validated());
        if($page){
            return redirect()->back()->with('success','Page Created Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Added, try again!');
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit',compact('page'));
    }

    public function update(Request $request, string $id)
    {
        $page = Page::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:pages,name,' . $page->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $page->update($validator->validated());
        if($page){
            return redirect()->back()->with('success','Page Updated Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Updated, try again!');
        }
    }

    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        if($page){
            $res = $page->delete();
            if($res){
                return back()->with('success','Page Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
