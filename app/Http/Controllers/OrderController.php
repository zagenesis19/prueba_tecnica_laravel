<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación estricta solicitada
        // distinct asegura que no haya products.*.product_id duplicados en el array
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id|distinct',
            'products.*.quantity' => 'required|integer|gt:0',
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $productsToAttach = [];

            // Calcular el total obteniendo el precio del producto desde la BD
            foreach ($request->products as $item) {
                // Se usa findOrFail por la garantía del exists, optimizamos un poco sumando al total
                $product = Product::findOrFail($item['product_id']);
                $total += $product->price * $item['quantity'];
                
                // Preparar array para attach() con los datos de la tabla pivote
                $productsToAttach[$item['product_id']] = ['quantity' => $item['quantity']];
            }

            // Crear el pedido con el total calculado
            $order = Order::create([
                'user_id' => $request->user_id,
                'total' => $total,
            ]);

            // Guardar los productos y sus cantidades en order_product
            $order->products()->attach($productsToAttach);

            DB::commit();

            return response()->json($order->load('products'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al procesar el pedido.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Se carga la orden con su usuario y productos (gracias al withPivot la cantidad vendrá incluida)
        $order = Order::with(['user', 'products'])->findOrFail($id);
        
        return response()->json($order);
    }
}
