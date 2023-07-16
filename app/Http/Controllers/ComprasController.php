<?php

namespace App\Http\Controllers;

use App\Models\Compras;
use Exception;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'mount' => 'required',
                'id_cliente' => 'required|exists:Clientes,id',
                'id_producto' => 'required|exists:Productos,id'
            ]);

            $compra = Compras::create([
                'mount' => $request->mount,
                'id_cliente' => $request->id_cliente,
                'id_producto' => $request->id_producto
            ]);

            return response()->json([
                'message' => 'Compra exitosa',
                'compra' => $compra
            ], 201);

        } catch (Exception $ex){
            return response()->json([
                'message' => "Error",
                'error' => $ex
            ], 418);
        }
    }
}
