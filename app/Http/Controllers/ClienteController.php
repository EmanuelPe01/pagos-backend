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

            $productos_pendientes = collect();
            $productos_pagados = collect();

            foreach($compras as $compra){
                $pagos = $compra->pagos;
                $producto = $compra->producto;
                $producto->id = $compra->id;
                $abono = 0;

                foreach($pagos as $pago){
                    $abono += round($pago->mount);
                }
                $restante = round($compra->mount - $abono);

                if($restante > 0) {
                    $productos_pendientes->push($producto);
                } else {
                    $productos_pagados->push($producto);
                }
            }

            $productos_pendientes = $productos_pendientes->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            })->map(function ($group) {
                return $group->values();
            });
            
            $productos_pagados = $productos_pagados->groupBy(function ($item) {
                return $item->created_at->format('d-m-Y');
            })->map(function ($group) {
                return $group->values();
            });

            return response()->json([
                'cliente' => $cliente,
                'pendientes' => $productos_pendientes,
                'pagados' => $productos_pagados
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
