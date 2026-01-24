<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Brand;
use Illuminate\Http\Request;


use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Validator;

class BrandController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Brand Show', only: ['index']),
            new Middleware('permission:Brand Create', only: ['create','store']),
            new Middleware('permission:Brand Edit', only: ['edit','update']),
            new Middleware('permission:Brand Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand.index',compact('brands'));
    }

    public function create()
    {
        $brands = Brand::where('parent_id',null)->get();
        return view('admin.brand.create',compact('brands'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:brands,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = createSlug($request->name,Brand::class);
        $brand->parent_id = $request->parent_id;
        $brand->description = $request->description;

        if ($request->hasFile('image')) {
            $brand->addMedia($request->file('image'))->toMediaCollection('brand');
        }

        $brand->is_visible = $request->is_visible;
        $brand->is_home = $request->is_home;
        $brand->is_popular = $request->is_popular;
        $brand->is_menu = $request->is_menu;
        $brand->is_special = $request->is_special;
        $res = $brand->save();
        if($res){
            return redirect()->back()->with('success','Brand Created Successfully');
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
        $brand = Brand::find($id);
        $brands = Brand::where('parent_id',null)->get();
        return view('admin.brand.edit',compact('brand','brands'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:brands,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $brand = Brand::find($id);
        if($brand->name != $request->name){
            $brand->slug = createSlug($request->name,Brand::class);
            $brand->name = $request->name;
        }
        $brand->parent_id = $request->parent_id;
        $brand->description = $request->description;

        if ($request->hasFile('image')) {
            $brand->clearMediaCollection('brand');
            $brand->addMedia($request->file('image'))->toMediaCollection('brand');
        }

        $brand->is_visible = $request->is_visible;
        $brand->is_home = $request->is_home;
        $brand->is_popular = $request->is_popular;
        $brand->is_menu = $request->is_menu;
        $brand->is_special = $request->is_special;
        $res = $brand->update();
        if($res){
            return redirect()->back()->with('success','Brand Updated Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Updated, try again!');
        }
    }

    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if($brand){
            $res = $brand->delete();
            if($res){
                return back()->with('success','Brand Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
