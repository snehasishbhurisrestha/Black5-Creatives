<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Category Show', only: ['index']),
            new Middleware('permission:Category Create', only: ['create','store']),
            new Middleware('permission:Category Edit', only: ['edit','update']),
            new Middleware('permission:Category Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $categorys = Category::all();
        return view('admin.category.index',compact('categorys'));
    }

    public function create()
    {
        $categorys = Category::where('parent_id',null)->get();
        return view('admin.category.create',compact('categorys'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:9216',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = new Category();
        $category->name = $request->name;
        // $category->slug = $request->slug;
        $category->slug = createSlug($request->name,Category::class);
        $category->parent_id = $request->parent_id;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('category');
        }

        if ($request->hasFile('banner_image')) {
            $category->addMedia($request->file('banner_image'))->toMediaCollection('category-banner');
        }

        $category->is_visible = $request->is_visible;
        $category->is_home = $request->is_home;
        $category->is_popular = $request->is_popular;
        $category->is_menu = $request->is_menu;
        $category->is_special = $request->is_special;
        $res = $category->save();
        if($res){
            return redirect()->back()->with('success','Category Created Successfully');
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
        $category = Category::find($id);
        $categorys = Category::where('parent_id',null)->get();
        return view('admin.category.edit',compact('category','categorys'));
    }

    public function update(Request $request, string $id)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:9216',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::find($id);
        // $category->slug = $request->slug;
        if($category->name != $request->name){
            $category->slug = createSlug($request->name,Category::class);
            $category->name = $request->name;
        }
        $category->parent_id = $request->parent_id;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $category->clearMediaCollection('category');
            $category->addMedia($request->file('image'))->toMediaCollection('category');
        }

        if ($request->hasFile('banner_image')) {
            $category->clearMediaCollection('category-banner');
            $category->addMedia($request->file('banner_image'))->toMediaCollection('category-banner');
        }

        $category->is_visible = $request->is_visible;
        $category->is_home = $request->is_home;
        $category->is_popular = $request->is_popular;
        $category->is_menu = $request->is_menu;
        $category->is_special = $request->is_special;
        $res = $category->update();
        if($res){
            return redirect()->back()->with('success','Category Updated Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Updated, try again!');
        }
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);
        if($category){
            $res = $category->delete();
            if($res){
                return back()->with('success','Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
