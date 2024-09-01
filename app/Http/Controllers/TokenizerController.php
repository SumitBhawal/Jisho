<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\WordMeaning;
use Illuminate\Support\Facades\Session;

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
        // Get all saved words from the database
        $wordMeanings = WordMeaning::all();
        //Saved word meanings in session for further use
        session()->put('tokens', $tokens);
        return view('search', ['tokens' => $tokens,'wordMeanings' => $wordMeanings,]);
    }
    
    public function tokenizeJapaneseText(String $japaneseText)
    {
        try {
            $response = Http::post('http://kuromoji_service:3000/tokenize', [
                'text' => $japaneseText,
            ]);

            // Check if the response is successful
            if ($response->successful()) {
                $tokens = $response->json();
            } elseif ($response->serverError()) {
                // Handle server errors (5xx)
                Log::error('Server error occurred: ' . $response->body());
                $tokens = [];
            } else {
                // Handle error response
                $tokens = [];
                $errorMessage = 'Error fetching tokens from the service.';
            }
        } catch (ConnectionException $e) {
            // Handle connection exceptions like timeouts
            Log::error('Connection error: ' . $e->getMessage());
            $tokens = [];
        } catch (RequestException $e) {
            // Handle other request-related exceptions
            Log::error('Request error: ' . $e->getMessage());
            $tokens = [];
        } 
        catch (\Exception $e) {
            // Catch any other exceptions
            $tokens = [];
            $errorMessage = 'Exception occurred: ' . $e->getMessage();
            Log::error('Exception occurred while tokenizing: ' . $e->getMessage());
        }

        // Fetch meanings for each token
        if (!empty($tokens)) {
            $arrayOfTokens = $this->fetchMeanings($tokens);
            $objectOfMeanings = $this->parseMeanings($tokens, $arrayOfTokens);
        } else {
            $arrayOfTokens = [];
        }
        return $objectOfMeanings;
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

                if ($response->successful()) {
                    $meaning = $response->json();
                    $meanings[$word] = $meaning;
                } elseif ($response->serverError()) {
                    // Handle server errors (5xx)
                    Log::error('Server error occurred: ' . $response->body());
                    $$meanings[$word] = [];
                } else {
                    $meanings[$word] = 'No meaning found.';
                }
            } catch (ConnectionException $e) {
                // Handle connection exceptions like timeouts
                Log::error('Connection error: ' . $e->getMessage());
                $tokens = [];
            } catch (RequestException $e) {
                // Handle other request-related exceptions
                Log::error('Request error: ' . $e->getMessage());
                $tokens = [];
            } catch (\Exception $e) {
                // Catch any other exceptions
                Log::error('Error in fething Meanings: ' . $e->getMessage());
                $tokens = [];
            }
        }
        // dd($meanings);
        return $meanings;
    }

    private function parseMeanings(array $tokens, array $meaningsArray)
    {
        $englishMeanings = [];
    
        foreach ($tokens as $token) {
            $word = $token['surface_form'];
            // Check if the given token is お, it should be considered a prefix instead of searching meaning
            if($word === "お"){
                $englishMeanings[$word] = "Honorofic Prefix";
            } else {
                $wordMeaning = data_get($meaningsArray, "{$word}.data.0.senses.0.english_definitions.0");
                if (!empty($wordMeaning)) {
                    $englishMeanings[$word] = $wordMeaning;
                } else {
                    if (strpos($wordMeaning, '、') !== false || strpos($wordMeaning, '。') !== false) {
                        continue;
                    } else {
                        Log::error("Error in fetching meaning");
                    }
                }
            }
        }
    
        return $englishMeanings;
    }
    
    public function showTokenizedWords(Request $request)
    {
        // Retrieve tokenized words from the Session
        if(session()->has('tokens')) {
            $tokens = session()->get('tokens');
            $wordMeanings = WordMeaning::all(); 
        } else {
            $tokens = [];
        }
        return view('search', ['tokens' => $tokens,'wordMeanings' => $wordMeanings,]);
    }
}
