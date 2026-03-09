<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Catálogo de Productos</h1>
    <hr>
    
    @if($products->isEmpty())
        <p>No hay productos disponibles por el momento.</p>
    @else
        <ul>
            @foreach($products as $product)
                <li>
                    <strong>{{ $product->name }}</strong> - ${{ number_format($product->price, 2) }}
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
