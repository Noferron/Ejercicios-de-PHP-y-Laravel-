<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <div style="text-align: center;">
    <h1>¡Pokémon Encontrado: {{ ucfirst($pokemon['name']) }}!</h1>
    
    <img src="{{ $pokemon['sprites']['other']['showdown']['front_default'] }}" alt="{{ $pokemon['name'] }}" width="200">

    <h3>Habilidades:</h3>
    <ul>
        @foreach($pokemon['abilities'] as $item)
            <li>{{ $item['ability']['name'] }}</li>
        @endforeach
    </ul>

    <p>Peso: {{ $pokemon['weight'] / 10 }} kg</p>
</div>
    
</body>
</html>