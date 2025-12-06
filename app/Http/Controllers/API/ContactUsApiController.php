<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactUs;

class ContactUsApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits:10|regex:/^[6789]/',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return apiResponse(false,'Validation Errors',['errors' => $validator->errors()],422);
        }

        try {
            $contact = new ContactUs();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->subject = $request->subject;
            $contact->message = $request->message;
            $contact->save();

            return apiResponse(true,'Thank you for reaching out! Your message has been successfully submitted. Our team will review your request and get back to you soon.',null,201);

        } catch (\Exception $e) {
            return apiResponse(false,'Something went wrong. Please try again.',['error' => $e->getMessage()],500);
        }
    }

}
