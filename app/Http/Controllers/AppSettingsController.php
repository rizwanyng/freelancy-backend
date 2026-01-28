<?php

namespace App\Http\Controllers;

use App\Models\FeatureSetting;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    /**
     * Get all master feature toggles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeatures()
    {
        $features = FeatureSetting::all()->pluck('is_enabled', 'key');

        return response()->json([
            'features' => $features,
            'server_time' => now()->toIso8601String(),
        ]);
    }
}
