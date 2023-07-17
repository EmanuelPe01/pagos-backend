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
                        'adeudo' => round($restante - $pago->mount)
                    ], 201);
                } else {
                    return response()->json([
                        'message' => 'La cantidad excede el adeudo',
                    ], 418);
                }
            } else {
                $pago = Pagos::create([
                    'mount' => $request->mount,
                    'id_compra' => $request->id_compra
                ]);

                $compra = Compras::find($pago->id_compra);
                $restante = round($compra->mount - $pago->mount);

                return response()->json([
                    'message' => 'Pago registrado exitosamente',
                    'informacion_pago' => $pago,
                    'adeudo' => round($restante - $pago->mount)
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

    public function editPayment($id, Request $request)
    {
        try
        {
            $payment = Pagos::find($id);
            $request->validate([
                'mount' => 'required',
                'id_compra' => 'required|exists:Compras,id'
            ]);

            $compra = Compras::find($request->id_compra);
            $pagos = $compra->pagos;
            $abono = 0;

            foreach($pagos as $pago){
                $abono += round($pago->mount);
            }

            $restante = round($compra->mount - $abono + $payment->mount);

            if (round($request->mount) <= $restante){
                $payment->mount = $request->mount;
                $payment->save();

                return response()->json([
                    'message' => 'Pago registrado exitosamente',
                    'informacion_pago' => $payment,
                    'adeudo' => round($restante - $payment->mount)
                ], 201);
            } else {
                return response()->json([
                    'message' => 'La cantidad excede el adeudo',
                ], 418);
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

    public function getForBuy($id)
    {
        try
        {
            $compra = Compras::find($id);
            $pagos = $compra->pagos;
            $pagos->makeHidden('id_compra');
            
            return response()->json([
               'pagos' => $pagos,
               'mount' => $compra->mount
            ], 200);
        }
        catch(Exception $ex)
        {
            return response()->json([
                'message' => 'Error',
                'error' => $ex
            ], 418);
        }
    }
}
