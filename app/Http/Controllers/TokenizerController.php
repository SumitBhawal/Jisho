<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TokenizerController extends Controller
{
    // Method to show the search form
    public function index()
    {
        return view('search');
    }

    // Method to handle the form submission and tokenize the Japanese text
    public function tokenize(Request $request)
    {
        $japaneseText = $request->input('text');
        // Tokenize the Japanese text using Kuromoji
        $tokens = $this->tokenizeJapaneseText($japaneseText);
        //return to search page with tokens
        return view('search', ['tokens' => $tokens]);
    }
    
    public function tokenizeJapaneseText(String $japaneseText)
    {
        try {
            $response = Http::post('http://kuromoji_service:3000/tokenize', [
                'text' => $japaneseText,
            ]);

            // Check if the response is successful
            if ($response->ok()) {
                $tokens = $response->json();
            } else {
                // Handle error response
                $tokens = [];
                // Optionally add an error message
                $errorMessage = 'Error fetching tokens from the service.';
            }
        } catch (\Exception $e) {
            // Handle exception
            $tokens = [];
            $errorMessage = 'Exception occurred: ' . $e->getMessage();
            Log::error('Exception occurred while tokenizing: ' . $e->getMessage());
        }

        // Fetch meanings for each token
        if (!empty($tokens)) {
            $englishMeaning = [];
            $arrayOfTokens = $this->fetchMeanings($tokens);
            // foreach ($tokens as $token) {
            // $englishMeaning = data_get($arrayOfTokens, "{$token['surface_form']}.data.0.senses.0.english_definitions.0");
            // dd($tokens);
            // }
            
        } else {
            $arrayOfTokens = [];
        }
        return $tokens;
    }

    private function fetchMeanings(array $tokens)
    {
        $meanings = [];
        foreach ($tokens as $token) {
            $word = $token['surface_form'];

            // Send request to meaning API
            try {
                $response = Http::get('https://jisho.org/api/v1/search/words', [
                    'keyword' => $word,
                ]);

                if ($response->ok()) {
                    $meaning = $response->json();
                    $meanings[$word] = $meaning;
                } else {
                    $meanings[$word] = 'No meaning found.';
                }
            } catch (\Exception $e) {
                $meanings[$word] = 'Error fetching meaning.';
                Log::error('Exception occurred while fetching meaning: ' . $e->getMessage());
            }
        }
        // return $meanings;
        return $meanings;
    }
    
}
