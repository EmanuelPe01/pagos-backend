<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:productos',
                'price' => 'required'
            ]);

            $producto = Producto::create([
                'name' => $request->name,
                'price' => $request->price
            ]);

            return response()->json([
                'message' => 'Producto registrado',
                'producto' => $producto
            ], 201);

        } catch (Exception $ex){
            return response()->json([
                'message' => 'error',
                'description' => $ex
            ], 418);
        }
    }

    public function getAll()
    {
        try 
        {
            $productos = Producto::all();

            return response()->json([
                'productos' => $productos
            ], 200);
        }
        catch (Exception $ex)
        {
            return response()->json([
                'message' => 'error',
                'description' => $ex
            ], 418);
        }
    }
}
