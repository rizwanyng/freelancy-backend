<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    /**
     * Enhance proposal text using Gemini AI.
     */
    public function enhanceProposal(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:10',
            'style' => 'nullable|string' // e.g., 'Corporate', 'Creative'
        ]);

        $apiKey = config('services.gemini.key');
        
        if (!$apiKey) {
            // FALLBACK: Magic Template Enhancer (FREE / No API required)
            $templates = [
                "I will help you with this project by using my skills to deliver a high-quality result.",
                "Leveraging my expertise, I will execute this project with precision and a focus on long-term value.",
                "Our proposed solution integrates modern industry standards with a tailored approach for your specific brand.",
                "This project scope focuses on scalability, security, and a premium user experience from start to finish."
            ];
            
            $text = $request->text;
            $enhanced = "Professional Proposal: " . $text . "\n\nKey Strategic Focus: " . $templates[array_rand($templates)];

            return response()->json([
                'success' => true,
                'text' => $enhanced,
                'is_mock' => true
            ]);
        }

        $prompt = "Rewrite the following business proposal description to be more " . ($request->style ?? 'professional and persuasive') . ". ";
        $prompt .= "Make it sound high-end, build trust, and clearly communicate value. Keep it around the same length. \n\nText: " . $request->text;

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $enhancedText = $response->json('candidates.0.content.parts.0.text');
                return response()->json([
                    'success' => true,
                    'text' => trim($enhancedText)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'AI Service temporarily unavailable',
                'error' => $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error communicating with AI service'
            ], 500);
        }
    }
}
