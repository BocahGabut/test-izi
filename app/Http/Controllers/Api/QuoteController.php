<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuoteController extends Controller
{
    public function getRandomChuckNorrisJoke()
    {
        $response = Http::get('https://api.chucknorris.io/jokes/random');

        return $response->json();
    }

    public function getRandomQuote()
    {
        $sources = [
            'Chuck Norris' => 'https://api.chucknorris.io/jokes/random',
            'Dog Fact' => 'http://dog-api.kinduff.com/api/facts?number=1',
            'Cat Fact' => 'https://catfact.ninja/fact',
        ];

        $randomSource = array_rand($sources);
        $apiUrl = $sources[$randomSource];

        $response = Http::get($apiUrl);
        $data = $response->json();

        if ($data) {
            return response()->json([
                'quote'   => $data['value'] ?? $data['fact'],
                'status'  => 'success',
                'source'  => $randomSource,
            ], 200);
        }

        return response()->json([
            'quote'   => null,
            'status'  => 'error',
            'source'  => null,
        ], 404);
    }

    public function getRandomDogFact()
    {
        //link not available
        // $response = Http::get('https://dog-facts-api.herokuapp.com/api/v1/resources/dogs?number=1');
        $response = Http::get('http://dog-api.kinduff.com/api/facts?number=1');

        return $response->json();
    }

    public function getRandomCatFact()
    {
        $response = Http::get('https://catfact.ninja/fact');

        return $response->json();
    }
}
