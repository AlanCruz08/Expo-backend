<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\RespuestaAPI;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{

    use RespuestaAPI;

    protected $reglasLogin =
    [
        'email'     => 'required|string|max:60',
        'password'  => 'required|string|max:60',
    ];

    protected $reglasRegister =
    [
        'name'      => 'required|string|max:60',
        'email'     => 'required|string|max:60',
        'password'  => 'required|string|max:60',
    ];

    public function login(Request $request)
    {
        //Se valida la solicitud
        $validacion = Validator::make($request->all(), $this->reglasLogin);

        //Si la validacion falla, se retorna un error
        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        //Se obtienen los datos del usuario
        $user = User::where('email', $request->email)->first();

        //Si el usuario no existe, se retorna un error
        if (!$user)
            return response()->json([
                'msg' => 'Usuario no encontrado',
                'data' => 'error',
                'status' => '404'
            ], 404);

        //Si la contraseña no coincide, se retorna un error
        if (!Hash::check($request->password, $user->password))
            return response()->json([
                'msg' => 'Contraseña incorrecta',
                'data' => 'error',
                'status' => '401'
            ], 401);

        //Se genera el token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        //Se retorna la respuesta
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function register(Request $request)
    {
        //Se valida la solicitud
        $validacion = Validator::make($request->all(), $this->reglasRegister);

        //Si la validacion falla, se retorna un error
        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        $user = User::where('email', $request->email)->first();

        if ($user)
            return response()->json([
                'msg' => 'Usuario ya existente',
                'data' => $user,
                'status' => '422'
            ], 422);

        //Se crea el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Se genera el token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        //Se retorna la respuesta sin success
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'msg' => 'Sesión cerrada',
            'status' => 'success'
        ], 200);
    }
}
