<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body>
        <p>{{config('app.name', 'MiProyecto')}}  </p>
        <p>{{env('APP_NAME', 'MiProyecto')}}  </p>
        <h1>Hola</h1>
        <p>Nombre: {{$producto->nombre}}</p>
        <p>Descripción: {{$producto->descripcion}} </p>
        <p>Precio: {{$producto->precio}}</p>
        <p>Stock:{{$producto->stock}}</p>
        <p>Id:{{$producto->id}}</p>

      @foreach ($productos as $producto)
        <p>Nombre: {{$producto->nombre}}</p>
        <p>Descripción: {{$producto->descripcion}} </p>
        <p>Precio: {{$producto->precio}}</p>
        <p>Stock:{{$producto->stock}}</p>
        <p>Id:{{$producto->id}}</p>
      @endforeach
    </body>