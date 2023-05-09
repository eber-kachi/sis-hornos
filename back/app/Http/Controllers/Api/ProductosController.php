<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductosController extends Controller
{

    public function index()
    {
        $productos = Producto::orderBy('id', 'desc')->get();

        $data = $productos->transform(function ($productos) {
            return $this->transform($productos);
        });

        return $this->successResponse(
            'Productos were successfully retrieved.',
            $data
        );

    }



    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

                // Crear el producto
                $producto = new Producto();
                $producto->nombre = $request->nombre;
                $producto->precio_unitario = $request->precio_unitario;
                $producto->caracteristicas = $request->caracteristicas;
                $producto->costo = $request->costo;
                $producto->save();
                // Preparar el array para sincronizar los materiales
                foreach ($request->materiales as $index => $material) {
                    $materiales[$material['material_id']] = [
                        'cantidad' => $material['cantidad'],
                        'descripcion' => $material['descripcion'] ?? null
                    ];
                }
            
               
                // Sincronizar los materiales con los datos adicionales
                $producto->materials()->sync($materiales);
            

            return $this->successResponse(
                'Produtos was successfully added.',
                $this->transform($producto)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Productos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {   $productos = Producto::findOrFail($id);
        return $this->successResponse(
            'Produtos was successfully retrieved.',
            $this->transform($productos)
        );
    }

    /**
     * Update the specified Productos in the storage.
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

            $productos = Producto::findOrFail($id);
            $productos->update($data);

            return $this->successResponse(
                'Produtos was successfully updated.',
                $this->transform($productos)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Productos from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $productos = Producto::findOrFail($id);
            $productos->delete();

            return $this->successResponse(
                'Materiles was successfully deleted.',
                $this->transform($productos)
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
            "nombre" => "required|string",
            "caracteristicas" => "nullable|string",
            "precio_unitario" => "nullable|numeric|min:0",
            "costo" => "nullable|numeric|min:0",
            "materiales.*.cantidad" => "required|integer|min:1",
            "materiales.*.descripcion" => "nullable|string|max:255",
            "materiales.*.material_id" => "required"
            
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
            "nombre" => "required|string",
            "caracteristicas" => "nullable|string",
            "precio_unitario" => "nullable|numeric|min:0",
            "costo" => "nullable|numeric|min:0",
            "materiales.*.cantidad" => "required|integer|min:1",
            "materiales.*.descripcion" => "nullable|string|max:255",
            "materiales.*.material_id" => "required"
         ];
        $data = $request->validate($rules);
        return $data;
    }

    /**
     * Transform the giving Productos to public friendly array
     *
     * @param App\Models\Productos $productos
     *
     * @return array
     */
    protected function transform(Producto $productos)
    {
        return [
            'id' => $productos->id,
            'nombre' => $productos->nombre,
            'caracteristicas' => $productos->caracteristicas,
            'precio_unitario' => $productos->precio_unitario,
            'costo' => $productos->costo,
        ];
    }
}
