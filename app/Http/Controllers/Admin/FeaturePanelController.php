<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\FeaturePanel;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Validator;

class FeaturePanelController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Feature Panel Show', only: ['index']),
            new Middleware('permission:Feature Panel Create', only: ['create','store']),
            new Middleware('permission:Feature Panel Edit', only: ['edit','update']),
            new Middleware('permission:Feature Panel Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $feature_panels = FeaturePanel::all();
        return view('admin.feature_panel.index',compact('feature_panels'));
    }

    public function create()
    {
        $categorys = Category::where('is_visible',1)->where('parent_id',null)->get();
        return view('admin.feature_panel.create',compact('categorys'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_visible' => 'required|in:0,1'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $feature_panel = new FeaturePanel();
        $feature_panel->category_id = $request->category_id;
        $feature_panel->title = $request->title;
        $feature_panel->description = $request->description;

        if ($request->hasFile('image')) {
            $feature_panel->addMedia($request->file('image'))->toMediaCollection('feature-panel');
        }

        $feature_panel->is_visible = $request->is_visible;
        $res = $feature_panel->save();
        if($res){
            return redirect()->back()->with('success','Feature Panel Created Successfully');
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
        $feature_panel = FeaturePanel::findOrFail($id);
        $categorys = Category::where('is_visible',1)->where('parent_id',null)->get();
        return view('admin.feature_panel.edit',compact('feature_panel','categorys'));
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

        $feature_panel = FeaturePanel::findOrFail($id);
        $feature_panel->category_id = $request->category_id;
        $feature_panel->title = $request->title;
        $feature_panel->description = $request->description;

        if ($request->hasFile('image')) {
            $feature_panel->clearMediaCollection('feature-panel');
            $feature_panel->addMedia($request->file('image'))->toMediaCollection('feature-panel');
        }

        $feature_panel->is_visible = $request->is_visible;
        $res = $feature_panel->update();
        if($res){
            return redirect()->back()->with('success','Feature Panel Updated Successfully');
        }else{
            return redirect()->back()->with('error','Data Not Updated, try again!');
        }
    }

    public function destroy(string $id)
    {
        $feature_panel = FeaturePanel::find($id);
        if($feature_panel){
            $res = $feature_panel->delete();
            if($res){
                return back()->with('success','Feature Panel Deleted Successfully');
            }else{
                return back()->with('error','Not Deleted');
            }
        }else{
            return back()->with('error','Not Found');
        }
    }
}
