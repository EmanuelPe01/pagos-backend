<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Compras;
use App\Models\Pagos;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;

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

            $compras->groupBy(function ($compra) {
                return $compra->created_at->format('d-m-Y');
            })->each(function ($compras, $fecha) use (&$productos_pendientes, &$productos_pagados) {
                $pendientes = collect();
                $pagados = collect();
                foreach($compras as $compra){
                    $pagos = $compra->pagos;
                    $producto  = $compra->producto;
                    $producto->id = $compra->id;
                    $abono = 0;
                    foreach($pagos as $pago){
                        $abono += round($pago->mount);
                    }
                    $restante = round($compra->mount - $abono);
                    
                    if($restante > 0){
                        $pendientes->push($producto);
                    } else {
                        $pagados->push($producto);
                    }
                }
                if($pendientes->count() > 0){
                    $productos_pendientes[$fecha] = $pendientes;
                }

                if($pagados->count() > 0){
                    $productos_pagados[$fecha] = $pagados;
                }
            });
            return response()->json([
                'cliente' => $cliente,
                'pendientes' => $productos_pendientes,
                'pagados' => $productos_pagados,
            ], 200);
        } catch (Exception $ex){
            return response()->json([
                'message' => 'error',
                'description' => $ex
            ], 418);
        }
    }

    public function showWithoutFilter($id)
    {
        try {
            $cliente = Cliente::find($id);
            $cliente->makeHidden('compras');
            $compras = $cliente->compras;

            $productos = collect();

            foreach($compras as $compra){
                $producto = $compra->producto;
                $producto->id = $compra->id;
                $producto->price = $compra->mount;
                $producto->created_at = $compra->created_at;
                $productos->push($producto);
            }

            return response()->json([
                'cliente' => $cliente,
                'compras' => $productos
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
