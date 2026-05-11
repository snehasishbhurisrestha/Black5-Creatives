<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Enquiry;
use Illuminate\Support\Str;

class EnquiryApiController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'phone_no'    => 'required|digits:10|regex:/^[6789]/',
            'description' => 'nullable',
            'phone_model' => 'nullable|string|max:100',
            'image'       => 'nullable|string', // Base64 Image
        ]);

        if ($validator->fails()) {
            return apiResponse(false,'Validation Errors',['errors' => $validator->errors()],422);
        }

        try {
            // Create enquiry
            $enquiry = Enquiry::create([
                'phone_no'    => $request->phone_no,
                'description' => $request->description,
                'phone_model' => $request->phone_model,
            ]);

            // Handle Base64 Image

            if ($request->filled('image')) {

                $base64 = preg_replace('/\s+/', '', $request->image);

                if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
                    return response()->json(['error' => 'Invalid image format'], 422);
                }

                $extension = $type[1];
                $imageData = base64_decode(
                    substr($base64, strpos($base64, ',') + 1),
                    true
                );

                if ($imageData === false) {
                    return response()->json(['error' => 'Decode failed'], 422);
                }

                $enquiry
                    ->addMediaFromString($imageData)
                    ->usingFileName(Str::uuid() . '.' . $extension)
                    ->toMediaCollection('enquiry_image');
            }

            return apiResponse(true,'Thank you for reaching out! Your request has been successfully submitted. Our team will review your request and get back to you soon.',null,201);
    
        } catch (\Exception $e) {
            return apiResponse(false,'Something went wrong. Please try again.',['error' => $e->getMessage()],500);
        }
    }
}
