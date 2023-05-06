<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\MaterialProductos;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MaterialProductosController extends Controller
{
    public function index()
    {
        $material_producto = MaterialProductos::paginate(25);

        $data = $material_producto->transform(function ($material_producto) {
            return $this->transform($material_producto);
        });

        return $this->successResponse(
            'MaterialProductos were successfully retrieved.',
            $data
           // $material_producto
        );

    }

    public function indexProductoMaterial()
{
    $data= Producto::with('materials')->get();
    

    return $this->successResponse(
        'MaterialProductos were successfully retrieved.',
        $data
       // $material_producto
    );
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
                'Material Produccion was successfully added.',
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
            'Material Produccion was successfully retrieved.',
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
                'Material Produccion was successfully updated.',
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
            "cantidad" => "nullable",
            "descripcion" => "nullable",
            "kg" => "nullable",
            "largo_cm" => "nullable",
            "ancho_cm" => "nullable",
            "cm2" => "nullable",
            'enabled' => 'boolean',
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
            "cantidad" => "nullable",
            "descripcion" => "nullable",
            "kg" => "nullable",
            "largo_cm" => "nullable",
            "ancho_cm" => "nullable",
            "cm2" => "nullable",
            'enabled' => 'boolean',


        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


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
            'kg' => $material_producto->kg,
            'largo_cm' => $material_producto->largo_cm,
            'ancho_cm' => $material_producto->ancho_cm,
            'producto_id' => $material_producto->producto_id,
            'material_id' => $material_producto->material_id,
            'cm2' => $material_producto->cm2,


        ];
    }



}
