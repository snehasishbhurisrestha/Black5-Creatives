<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Slider;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Validator;

class SliderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Slider Show', only: ['index']),
            new Middleware('permission:Slider Create', only: ['create','store']),
            new Middleware('permission:Slider Edit', only: ['edit','update']),
            new Middleware('permission:Slider Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $sliders = Slider::all();
        return view('admin.slider.index',compact('sliders'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->description = $request->description;

        if ($request->hasFile('image')) {
            $slider->addMedia($request->file('image'))->toMediaCollection('slider');
        }

        $slider->is_visible = $request->is_visible;
        $res = $slider->save();
        if($res){
            return redirect()->back()->with('success','Slider Created Successfully');
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
        $slider = Slider::findOrFail($id);
        return view('admin.slider.edit',compact('slider'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slider = Slider::findOrFail($id);
        $slider->title = $request->title;
        $slider->description = $request->description;

        if ($request->hasFile('image')) {
            $slider->clearMediaCollection('slider');
            $slider->addMedia($request->file('image'))->toMediaCollection('slider');
        }

        $slider->is_visible = $request->is_visible;
        $res = $slider->update();
        if($res){
            return redirect()->back()->with('success','Slider Updated Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Updated, try again!');
        }
    }

    public function destroy(string $id)
    {
        $slider = Slider::find($id);
        if($slider){
            $res = $slider->delete();
            if($res){
                return back()->with('success','Slider Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
