<?php

namespace App\Http\Controllers;

use App\Models\Compras;
use App\Models\Pagos;
use Exception;
use Illuminate\Http\Request;

class PagosController extends Controller
{
    public function store(Request $request)
    {
        try 
        {
            $request->validate([
                'mount' => 'required',
                'id_compra' => 'required|exists:Compras,id'
            ]);

            $compra = Compras::find($request->id_compra);
            $pagos = $compra->pagos;

            if($pagos){
                $abono = 0;

                foreach($pagos as $pago){
                    $abono += round($pago->mount);
                }

                $restante = round($compra->mount - $abono);

                if (round($request->mount) <= $restante){
                    $pago = Pagos::create([
                        'mount' => $request->mount,
                        'id_compra' => $request->id_compra
                    ]);

                    return response()->json([
                        'message' => 'Pago registrado exitosamente',
                        'informacion_pago' => $pago,
                        'restante' => round($restante-$request->mount)
                    ], 201);
                } else {
                    return response()->json([
                        'message' => 'La cantidad excede el adeudo',
                        'restante' => round($restante)
                    ], 201);
                }
            } else {
                $pago = Pagos::create([
                    'mount' => $request->mount,
                    'id_compra' => $request->id_compra
                ]);

                return response()->json([
                    'message' => 'Pago registrado exitosamente',
                    'informacion_pago' => $pago
                ], 201);
            }          
        }
        catch (Exception $ex)
        {
            return response()->json([
                'message' => 'Error',
                'error' => $ex
            ], 418);
        }
    }
}
