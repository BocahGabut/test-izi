<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('api.register');
        Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('api.login');
    });


    //quotes
    Route::get('/quote', 'App\Http\Controllers\Api\QuoteController@getRandomQuote')->name('api.random-quotes-fact');
    Route::get('/random/chuck-norris-joke', 'App\Http\Controllers\Api\QuoteController@getRandomChuckNorrisJoke');
    Route::get('/random/dog-fact', 'App\Http\Controllers\Api\QuoteController@getRandomDogFact');
    Route::get('/random/cat-fact', 'App\Http\Controllers\Api\QuoteController@getRandomCatFact');

    //transaction
    Route::post('transaction', 'App\Http\Controllers\Api\TransactionController@storeTransaction');
    Route::post('transaction/get', 'App\Http\Controllers\Api\TransactionController@getData');
});

