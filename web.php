<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\saludosController;
use App\Http\Controllers\PokemonController;

Route::get('/',[saludosController::class, 'index']);


Route::get('/saludo', function(){
    return view('welcome');
});

Route::get('/pokemon/{nombre}', [PokemonController::class, 'obtenerPokemon']);
