<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function obtenerPokemon($nombre){
        $direccion=env('POKE_URL', 'https://pokeapi.co/api/v2/pokemon/').$nombre;

        $respuesta=Http::timeout(20)->get($direccion);

        if($respuesta->successful()){
            $pokemon = $respuesta->json();
            return view ('pokemon', compact('pokemon'));

        }
        return 'No de encontró a ese Pokemon';


    }
}
