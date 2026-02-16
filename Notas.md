# Como consumir una API con laravel

## Primer paso: Traer la URL al archivo .env
Para esto creamos una variable con el nombre que queramos y luego, simplemente, pegamos la dirección url de la API sin comillas y sin espacios **IMPORTANTE**

```env
POKE_URL=https://pokeapi.co/api/v2/pokemon/ditto
```

## Segundo paso: Crear un controlador
Debemos crear un controlador para conectarnos a la API y poder traer los datos de ésta. 

**Nota: Para crear el controllador usamos `php artisan make:controllador PokemonControllador` en el terminal**

1. Creamos una función public que usaremos para llamarla en la ruta y obtener los datos de forma dinámica `    public function obtenerPokemon($nombre)`. 

2. Creamos una variable donde le indicamos donde está la URL de la API en nuestros archivos `$direccion=env('POKE_URL', 'https://pokeapi.co/api/v2/pokemon/').$nombre; ` donde también podemos indicarle una segunda opción por si no pudiera encontrar la variable en nuestro archivo .env.

3. Una vez tenemos la dirección creamos una petición get para traer los datos, a la cuál también podemos añadir un tiempo de espera máximo con `Http::timeout(20)` y quedaría así 
```php
$respuesta=Http::timeout(20)->get($direccion);
``` 

4. Comprobamos que la conexión con la API es correcta y para ello realizamos un condicional con el método `successful()` y si fuera correcta inicializamos una variable donde transformamos los datos, que vienen en formato JSON, a un array que si entiende el motor de PHP.
`$pokemon = $respuesta->json();` y retornamos a la vista que queremos los datos guardados en la variable **$pokemon**
```php
if($respuesta->successful()){
            $pokemon = $respuesta->json();
            //----Vista pokemon-----variable $pokemon
            return view ('pokemon', compact('pokemon'));

        }
```

5. Si no encuentra el pokemon en la API devolvemos el siguiente mensaje: `return 'No de encontró a ese Pokemon';`

Por lo que el controlador quedaría de la siguiente forma:

```php 
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
```

## Tercer paso: Creamos la ruta

En este caso y como la API la optenemos de una URL no necesitamos crear un modelo para consumirla y consultarla, ya con el controlador traemos todos los datos en el array pokemon y para poder mostrarlo en nuestra vista, como le pedimos en el **return** debemos crear una ruta. 

En este caso usé el mismo archivo de rutas que usé para el resto de vistas. Entonces lo que debemos hacer será lo siguiente: 

1. Importamos el controller `use App\Http\Controllers\PokemonController;`
2. Creamos la ruta solo para la petición **GET** `Route::get()`
3. Dentrode la ruta indicamos a que dirección queremos que petenezca o, mejor explicado, donde queremos que se muestren los datos que será la dirección de nuestro proyecto + la dirección que indiquemos `Route::get('/pokemo/{$nombre}')` donde la ruta **$nombre** se genererá de forma dinámica dependiendo del pokemon que estemos consultando. 

Seguido de esto indicamos de donde tiene que obtener los datos `[PokemonController::class, 'obtenerPokemon']`. Esto le indica que use el controlador **PokemonController::class** y la función **obtenerPokemon** de este controlador, ya que podemos tener muchas más funciones dentro, como por ejemplo, otra para añadir más pokemons a la lista, la cual tendría una petición **POST** y no **GET**. Pero como no podemos hacerlo ya que solo es una API de consulta y no de escritura nos quedaremos solo con lo que estamos haciendo ya. 

Entonces el archivo routes quedaría así: 

```php 
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::get('/pokemon/{nombre}', [PokemonController::class, 'obtenerPokemon']);
```

## Cuarto paso: Mostrar los datos de la API en la View
Ya tenemos la API conectada a nuestro proyecto desde el .env, el controlador tiene la función para obtener y traducir los datos de la API y la ruta para conectar estos datos con nuestra View. Ahora solo falta mostrar los datos que nos interesen en el navegador. 

Para traer los datos llamamos a la variable del controller **$pokemon** y le indicamos que dato queremos `$pokemon['name']`, pero claro, no todos los datos podemos traerlos de la misma forma ya que puede haber varios en esa propiedad, como por ejemplo ocurre con las habilidades. 

Como vemos se trata de una listado de habilidades y para ello debemos recorrer el array traer los datos de uno en uno. Para esto usamos **foreach** que en **Laravel** podemos hacerlo sin usar las llaves *<?php ?>* para ello usaremos *@* 

```html
@foreach($pokemon['abilities'] as $item)
    <li>{{ $item['ability']['name'] }}</li>
@endforeach
```

Aquí lo que estamos haciendo es sacar los datos de **$pokemon** y leer uno a uno guardando cada dato en la variable **$item** mientras lo lee y lo muestra. Para entenderlo, **$item** guarda cada dato de forma temporal hasta que se ejecuta la parte interna de **foreach** y muestra los datos en el navegador, una vez hecho esto lee el siguiente dato y vuelve a hacer lo mismo hasta que termine todo el listado de datos. Si el pokemos tiene 3 habiladades **$item** se inicializará con cada una de ellas, guardará temporalmente sus datos y los imprimirá en pantalla uno a uno borrando en el proceso la habilidad leída anteriormente. Se podría decir que su valor es temporal y solo dura el tiempo necesario como para poder imprimirlo en el navegador. 

En el caso de datos numéricos podemos realizar operaciones con ellos, por ejemplo: `<p>Peso: {{ $pokemon['weight'] / 10 }} kg</p>` en el que dividimos el peso obtenido de la API entre 10. 

Como traemos imágenes de la API? 
Pues usamos la la etiqueta `<img>` de HTML y en la **src**,en vez de indicar la url de una archivo o una url de una web, indicamos la propiedad donde la API tiene guardada las imagenes
Entonces para mostrar los datos de la API en la View quedaría de la siguiente forma: `<img src="{{ $pokemon['sprites']['front_default'] }}" alt="{{ $pokemon['name'] }}" width="200">` 
Aquí anidamos dos propiedades *sprites* y *front_default*. En *sprites* entramos en la imagenes, pero parece ser que hay varios tipos en la API por lo que debemos seleccionar una de ellas, la cuál será en la que vemos al pokemon de frente *front_default*

```html
<body>
   <div style="text-align: center;">
    <h1>¡Pokémon Encontrado: {{ ucfirst($pokemon['name']) }}!</h1>
    
    <img src="{{ $pokemon['sprites']['front_default'] }}" alt="{{ $pokemon['name'] }}" width="200">

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
```