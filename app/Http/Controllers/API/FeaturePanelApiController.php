<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeaturePanel;
use App\Models\Category;

class FeaturePanelApiController extends Controller
{
    // Fetch feature panels for Phone Case category
    public function phoneCaseFeaturePanel(Request $request)
    {
        $phone_case_id = env('PHONE_CASE_ID');

        $feature_panels = FeaturePanel::with('media') // eager load media
            ->where('category_id', $phone_case_id)
            ->where('is_visible', 1) // only visible panels
            ->get()
            ->map(function ($panel) {
                return [
                    'id' => $panel->id,
                    'title' => $panel->title,
                    'description' => $panel->description,
                    'image' => $panel->getFirstMediaUrl('feature-panel'), // get media URL
                    'created_at' => $panel->created_at,
                ];
            });

        return apiResponse(true,'Phone Case Feature Panel',$feature_panels,200);
    }

    // Fetch feature panels for Wall Art category
    public function wallArtFeaturePanel(Request $request)
    {
        $wall_art_id = env('WALL_ART_ID');

        $feature_panels = FeaturePanel::with('media')
            ->where('category_id', $wall_art_id)
            ->where('is_visible', 1)
            ->get()
            ->map(function ($panel) {
                return [
                    'id' => $panel->id,
                    'title' => $panel->title,
                    'description' => $panel->description,
                    'image' => $panel->getFirstMediaUrl('feature-panel'),
                    'created_at' => $panel->created_at,
                ];
            });

        return apiResponse(true,'Wall Art Feature Panel',$feature_panels,200);
    }
}
