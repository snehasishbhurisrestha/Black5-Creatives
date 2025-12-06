<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\ContactUs;

class SiteContactUsController extends Controller
{
    public function index()
    {
        return view('site.contact');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits:10|regex:/^[6789]/',
            'subject' => 'nullable',
            'message' => 'nullable',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $contact = new ContactUs();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->subject = $request->subject;
            $contact->message = $request->message;
            $res = $contact->save();

            if($res){
                return back()->with('success','Thank you for reaching out! Your message has been successfully submitted. Our team will review your request and get back to you as soon as possible. We look forward to connecting with you soon!');
            }else{
                return back()->with('error','Please try again');
            }
        }
    }
}
