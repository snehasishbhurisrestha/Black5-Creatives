<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationApiController extends Controller
{
    // Get all countries
    public function getCountries()
    {
        $countries = Country::select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    // Get states by country
    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $states
        ]);
    }

    // Get cities by state
    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }
}