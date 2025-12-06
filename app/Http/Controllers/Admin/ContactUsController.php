<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ContactUs;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactUsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:ContactUs Show', only: ['index']),
            // new Middleware('permission:ContactUs Create', only: ['create','store']),
            // new Middleware('permission:ContactUs Edit', only: ['edit','update']),
            new Middleware('permission:ContactUs Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $contacts = ContactUs::orderBy('id','desc')->get();
        return view('admin.contact-us.index',compact('contacts'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ContactUs $contactUs)
    {
        //
    }

    public function edit(ContactUs $contactUs)
    {
        //
    }

    public function update(Request $request, ContactUs $contactUs)
    {
        //
    }

    public function destroy(string $id)
    {
        $contact = ContactUs::findOrFail($id);
        if($contact){
            $res = $contact->delete();
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
