<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Traits\RespuestaAPI;
use Illuminate\Support\Facades\Validator;



class PersonasController extends Controller
{
  
    use RespuestaAPI;

    protected $reglas =
    [
        'nombre' => 'required|string|max:60',
        'apellido_p' => 'required|string|max:60',
        'apellido_m' => 'required|string|max:60',
    ];

    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $persona=Persona::all();
        return response()->json([
            'msg'   => 'Personas obtenidas correctamente',
            'data'  => $persona,
            'status'=> 201
        ], 201);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Se valida la solicitud
        $validacion = Validator::make($request->all(), $this->reglas);
        //Si la validacion falla, se retorna un error
        if ($validacion->fails()) 
         return response()->json([
            'msg'   => 'Error al crear la persona, datos incorrectos',
            'data'  => $validacion->errors(),
            'status'=> 422
        ], 422);   
        //return $this->error($validacion->errors(), 422);
       // en caso de no tener token retornar un error en json
        if (!$request->bearerToken()) 
            return $this->error('No se ha enviado el token', 401); 
        //Si la validacion no falla, se crea el registro
        
        $persona = Persona::create($validacion->validated());
        //return $this->exito(['persona'=>$persona]);

        if ($persona->save())
        return response()->json([
            'msg'   => 'Persona creada correctamente',
            'data'  => $persona,
            'status'=> 201
        ], 201);
        return response()->json([
            'msg'   => 'Error al crear la persona',
            'data'  => null,
            'status'=> 422
        ], 422);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //hola ;3git
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $persona_id)
    {
        //Se valida la solicitud
        $validacion = Validator::make($request->all(), $this->reglas);

        //Si la validacion falla, se retorna un error
        if ($validacion->fails()) 
            //return $this->error($validacion->errors());
            return response()->json([
                'msg'   => 'Error al actualizar la persona, datos incorrectos',
                'data'  => $validacion->errors(),
                'status'=> 422
            ], 422);

        // en caso de no tener token retornar un error en json
        if (!$request->bearerToken()) 
            return $this->error('No se ha enviado el token', 401);
            //buscar por id
            $persona = Persona::find($persona_id);
            if (!$persona)
                return $this->error('No se encontro la persona', 404);
        
                $persona->nombre = $request->nombre;
                $persona->apellido_p = $request->apellido_p;
                $persona->apellido_m = $request->apellido_m;
                $persona->save();

        //Si la validacion no falla, se actualiza el registro
       
        if($persona->save())
        return response()->json([
            'msg'   => 'Persona actualizada correctamente',
            'data'  => $persona,
            'status'=> 201
        ], 201);

        //si la persona no se actualizo correctamente
        else
            return response()->json([
                'msg'   => 'Error al actualizar la persona',
                'data'  => null,
                'status'=> 422
            ], 422);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($persona_id)
    {
        $persona = Persona::find($persona_id);
        if (!$persona){
            return response()->json([
                'msg'   => 'la persona no existe',
                'data'  => null,
                'status'=> 404
            ], 404);
        }else
        $persona->delete();
        return response()->json([
            'msg'   => 'Persona eliminada correctamente',
            'data'  => $persona,
            'status'=> 201
        ], 201);
        
    }
}