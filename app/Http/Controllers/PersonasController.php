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
            'data'  => $persona
        ], 200);

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
            return $this->error($validacion->errors(), 422);
       // en caso de no tener token retornar un error en json
        if (!$request->bearerToken()) 
            return $this->error('No se ha enviado el token', 401); 
        //Si la validacion no falla, se crea el registro
        
        $persona = Persona::create($validacion->validated());
        return $this->exito(['persona'=>$persona]);

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
    public function update(Request $request, Persona $persona)
    {
        //Se valida la solicitud
        $validacion = Validator::make($request->all(), $this->reglas);

        //Si la validacion falla, se retorna un error
        if ($validacion->fails()) 
            return $this->error($validacion->errors());

        // en caso de no tener token retornar un error en json
        if (!$request->bearerToken()) 
            return $this->error('No se ha enviado el token', 401);

        //Si la validacion no falla, se actualiza el registro
        $persona->update($validacion->validated());
        return $this->exito(['persona'=>$persona]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        $persona->delete();
        return $this->exito(['persona'=>$persona]);
         
    }
}
