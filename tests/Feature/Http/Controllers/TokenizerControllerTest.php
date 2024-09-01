<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use App\Models\WordMeaning;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\TokenizerController;
use Illuminate\Http\Request;

class TokenizerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testTokenizeJapaneseText()
    {
        // Mock the external API call for tokenization
        Http::fake([
            'http://kuromoji_service:3000/tokenize' => Http::response([
                [
                    'surface_form' => '日本',
                ],
                [
                    'surface_form' => 'が',
                ],
            ], 200),
        ]);

        // Mock the external API call for meaning lookup
        Http::fake([
            'https://jisho.org/api/v1/search/words?keyword=日本' => Http::response([
                'data' => [
                    [
                        'senses' => [
                            [
                                'english_definitions' => ['Japan'],
                            ],
                        ],
                    ],
                ],
            ], 200),
            'https://jisho.org/api/v1/search/words?keyword=が' => Http::response([
                'data' => [
                    [
                        'senses' => [
                            [
                                'english_definitions' => ['indicates the subject of a sentence'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $controller = new TokenizerController();

        // Call the tokenizeJapaneseText method
        $result = $controller->tokenizeJapaneseText('日本が');

        // Assert the structure and values of the result
        $this->assertEquals([
            '日本' => 'Japan',
            'が' => 'indicates the subject of a sentence',
        ], $result);
    }
}
