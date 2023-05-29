<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\LoteProduccion;
use App\Models\MaterialProductos;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;





class MaterialProductosController extends Controller
{
    public function index()
    {
        $material_producto = MaterialProductos::orderBy('id', 'desc')->get();

        $data = $material_producto->transform(function ($material_producto) {
            return $this->transform($material_producto);
        });

        return $this->successResponse(
            'MaterialProductos were successfully retrieved.',
            $data
           // $material_producto
        );

    }

    public function indexProductoMaterial($id_producto)
    {
        $descargar = request()->query('descargar');
        $producto = Producto::find($id_producto);
        if ($descargar == 'true') {
            // generar el pdf

            $data= $producto->materials->map(function ($material) {
                return [
                    'id' => $material->id,
                    'nombre' => $material->nombre,
                    'cantidad' => $material->pivot->cantidad,
                    'descripcion' => $material->pivot->descripcion,
                    'medida_nombre'=> optional($material->medidas)->nombre
                ];
            });
            $fecha = date('d/m/Y');
            $pdf = App::make('dompdf.wrapper');
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $pdf->loadView('reporte', ['datos' => $data, 'fecha' => $fecha,'producto' => $producto]); return $pdf->stream('reporte.pdf');

        } else {
            // mostrar la vista normal


            $data= $producto->materials->map(function ($material) {
                return [
                    'id' => $material->id,
                    'nombre' => $material->nombre,
                    'cantidad' => $material->pivot->cantidad,
                    'descripcion' => $material->pivot->descripcion,
                    'medida_nombre'=> optional($material->medidas)->nombre
                ];
            });

            return $this->successResponse(
                'MaterialProductos were successfully retrieved.',
                $data
            // $material_producto
            );

        }



    }


    public function materialProductoLotes($lote_produccion_id)
    {
        $descargar = request()->query('descargar');
        $lote = LoteProduccion::with('pedidos')->find($lote_produccion_id);
        foreach ($lote->pedidos as $pedido) {
            $id_productos= $pedido->producto_id;
        }
        $producto = Producto::find($id_productos); // Buscar el producto por su id

      //  echo $lote;
        if ($descargar == 'true') {
            // generar el pdf
            $data= $producto->materials->map(function ($material) use ($lote, $producto) {
                return [
                    'id' => $material->id,
                    'nombre' => $material->nombre,
                    'cantidad' => $material->pivot->cantidad,
                    'descripcion' => $material->pivot->descripcion,
                    'medida_nombre'=> optional($material->medidas)->nombre,
                    'producto_lote' => $material->pivot->cantidad * $lote->cantidad
                ];
            });
            $fecha = date('d/m/Y');
            $pdf = App::make('dompdf.wrapper');
            $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $pdf->loadView('reporteLote', ['datos' => $data, 'fecha' => $fecha,'producto' => $producto ,'lote' => $lote]); return $pdf->stream('reporteLote.pdf');
        } else {
            // mostrar la vista normal
        $data= $producto->materials->map(function ($material) use ($lote, $producto) {
            return [
                'id' => $material->id,
                'nombre' => $material->nombre,
                'cantidad' => $material->pivot->cantidad,
                'descripcion' => $material->pivot->descripcion,
                'medida_nombre'=> optional($material->medidas)->nombre,
                'producto_lote' => $material->pivot->cantidad * $lote->cantidad
            ];
        });




            return $this->successResponse(
                'MaterialProductos were successfully retrieved.',
                $data
            // $material_producto
            );

        }

    }



    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            $material_producto = MaterialProductos::create($data);

            return $this->successResponse(
                'Material Producto was successfully added.',
                $this->transform($material_producto)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified MaterialProductoss.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $material_producto = MaterialProductos::findOrFail($id);

        return $this->successResponse(
            'Material Producto was successfully retrieved.',
            $this->transform($material_producto)
        );
    }

    /**
     * Update the specified MaterialProductoss in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            $material_producto = MaterialProductos::findOrFail($id);
            $material_producto->update($data);

            return $this->successResponse(
                'Material Producto was successfully updated.',
                $this->transform($material_producto)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**successResponse
     * Remove the specified MaterialProductoss from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $material_producto = MaterialProductos::findOrFail($id);
            $material_producto->delete();

            return $this->successResponse(
                'MaterialProductos was successfully deleted.',
                $this->transform($material_producto)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Gets a new validator instance with the defined rules.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Facades\Validator
     */
    protected function getValidator(Request $request)
    {
        $rules = [
            "producto_id" => "required",
            "material_id" => "required",
            "cantidad" => "required",
            "descripcion" => "nullable",
        ];

        return Validator::make($request->all(), $rules);
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            "producto_id" => "required",
            "material_id" => "required",
            "cantidad" => "required",
            "descripcion" => "nullable",


        ];


        $data = $request->validate($rules);
        return $data;
    }

    /**
     * Transform the giving MaterialProductoss to public friendly array
     *
     * @param App\Models\MaterialProductos $material_producto
     *
     * @return array
     */
    protected function transform(MaterialProductos $material_producto)
    {

        return [
            'id' => $material_producto->id,
            'cantidad' => $material_producto->cantidad,
            'descripcion' => $material_producto->descripcion,
            'producto_id' => $material_producto->producto_id,
            'material_id' => $material_producto->material_id,


        ];
    }



}
