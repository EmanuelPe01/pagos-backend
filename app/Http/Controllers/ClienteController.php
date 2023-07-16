<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Exception;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:clientes'
            ]);

            $cliente = Cliente::create([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Cliente registrado',
                'cliente' => $cliente
            ], 201);

        } catch (Exception $ex){
            return response()->json([
                'message' => 'error',
                'description' => $ex
            ], 418);
        }
    }

    public function show($id)
    {
        try {
            $cliente = Cliente::find($id);
            $cliente->makeHidden('compras');
            $compras = $cliente->compras;
            $compras->makeHidden('id_cliente');
            $compras->makeHidden('id_producto');

            foreach($compras as $compra){
                $compra->producto;
                $compra->pagos;
            }

            return response()->json([
                'cliente' => $cliente,
                'compras' => $compras
            ], 200);
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
            $clientes = Cliente::all();

            return response()->json([
                'clientes' => $clientes
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
