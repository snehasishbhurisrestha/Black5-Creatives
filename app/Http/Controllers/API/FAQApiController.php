<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FAQ;

class FAQApiController extends Controller
{
    public function index()
    {
        $faqs = FAQ::where('is_visible',1)->orderBy('sort_order', 'asc')->get();
        return apiResponse(true, 'FAQ fetched successfully.', $faqs, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return apiResponse(false,'Validation Errors',['errors' => $validator->errors()],422);
        }

        try {
            $faq = new FAQ();
            $faq->question = $request->question;
            $faq->is_visible = 0;
            $faq->save();

            return apiResponse(true, 'Your question has been received. Our support team will review it and respond shortly.', null, 201);

        } catch (\Exception $e) {
            return apiResponse(false,'Something went wrong. Please try again.',['error' => $e->getMessage()],500);
        }
    }

}
