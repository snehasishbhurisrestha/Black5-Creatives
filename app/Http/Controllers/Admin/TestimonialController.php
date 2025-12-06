<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Testimonial;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Testimonial Show', only: ['index']),
            new Middleware('permission:Testimonial Create', only: ['create','store']),
            new Middleware('permission:Testimonial Edit', only: ['edit','update']),
            new Middleware('permission:Testimonial Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $testimonials = Testimonial::all();
        return view('admin.testimonial.index',compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'nullable|max:255',
            'message' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->address = $request->address;
        $testimonial->message = $request->message;
        $testimonial->rating = $request->rating ?? 0;

        if ($request->hasFile('image')) {
            $testimonial->addMedia($request->file('image'))->toMediaCollection('testimonial');
        }

        $testimonial->is_visible = $request->is_visible;
        $res = $testimonial->save();
        if($res){
            return redirect()->back()->with('success','Testimonial Created Successfully');
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
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit',compact('testimonial'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'nullable|max:255',
            'message' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->name = $request->name;
        $testimonial->address = $request->address;
        $testimonial->message = $request->message;
        $testimonial->rating = $request->rating ?? 0;

        if ($request->hasFile('image')) {
            $testimonial->clearMediaCollection('testimonial');
            $testimonial->addMedia($request->file('image'))->toMediaCollection('testimonial');
        }

        $testimonial->is_visible = $request->is_visible;
        $res = $testimonial->update();
        if($res){
            return redirect()->back()->with('success','Testimonial Updated Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Updated, try again!');
        }
    }

    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if($testimonial){
            $res = $testimonial->delete();
            if($res){
                return back()->with('success','testimonial Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
