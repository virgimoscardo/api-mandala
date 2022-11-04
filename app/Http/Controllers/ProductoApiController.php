<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoApiController extends Controller
{
    public function index(){
        $productos = Producto::all();
        $data = $productos->map(function($producto){
            return[
               'id'=> $producto->id,
               'nombre' => $producto->nombre,
               'descripcion' => $producto->descripcion,
               'precio' => $producto->precio,
               'porciones' => $producto->porciones,
               'url'=>route('api.productos.show', $producto)            
            ];
        });
        return response([
            'meta'=> [
                'count' => $data->count(),
                'path' => route('api.productos.index')
            ],
            'data'=>$data
        ],201);
       
    }

    public function show(Producto $producto){
        return [
            'meta' => [
                'path' => route('api.productos.show', $producto),
                'resource' => route('api.productos.index')
            ],
            'data' => [
                'id'=> $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'porciones' => $producto->porciones,
            ]
        ]; 
    }

    public function store(){

        $validator = Validator::make(request()->all(),[
            'nombre' => 'required',
            'descripcion' =>'nullable',
            'precio' => 'required|numeric',
            'porciones' => 'nullable'
        ],[
            'nombre.required' => 'Debe ingresar un nombre',            
            'precio.required' =>'Debe ingresar un precio',
            'precio.numeric' => 'Debe ingresar sólo números'
            
        ]);
        if ($validator -> fails()){
            return response ([
                'error'=>true,
                'data'=>$validator -> errors()
            ], 422);
        };

        $producto = Producto::create([
            'nombre' => request() -> nombre,
            'descripcion' => request() -> descripcion,
            'precio' => request() -> precio,
            'porciones' => request() -> porciones
        ]);
        return response([
            "metha" =>[
                "mensaje" => "Se creó el producto $producto->nombre",
                "codigo" => 201
            ],
            'data' => $producto
        ], 201);
        
    }

    public function update(Producto $producto){
        $producto ->update(request()->all());
        return response([
            "metha" =>[
                "mensaje" => "Se actualizó el producto $producto->nombre",
                "codigo" => 201
            ],
            'data' => $producto
        ], 201);
    }

    public function destroy(Producto $producto){
        $producto ->delete(request()->all());
        return response([
            "metha" =>[
                "mensaje" => "Se eliminó el producto $producto->nombre",
                "codigo" => 201
            ],
            'data' => $producto
        ], 201);
    }


}
