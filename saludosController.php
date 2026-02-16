<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Productos;


class saludosController extends Controller
{
   public function index(): View
   {
      $producto= Productos::find(1);
      $productos= Productos::all();
      
      //die(var_dump($productos));
      //dd($productos);
    return view("index",['productos'=>$productos,'producto'=>$producto,]);
      //return view('index', compact('productos'));
   } 
}
