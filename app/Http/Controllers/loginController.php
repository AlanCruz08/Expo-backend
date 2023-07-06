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
            return $this->error($validacion->errors(), 422);

        //Se obtienen los datos del usuario
        $user = User::where('email', $request->email)->first();

        //Si el usuario no existe, se retorna un error
        if (!$user)
            return $this->error('Usuario no encontrado', 404);

        //Si la contraseña no coincide, se retorna un error
        if (!Hash::check($request->password, $user->password))
            return $this->error('Contraseña incorrecta', 401);

        //Se genera el token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        //Se retorna la respuesta
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request)
    {
        //Se valida la solicitud
        $validacion = Validator::make($request->all(), $this->reglasRegister);

        //Si la validacion falla, se retorna un error
        if ($validacion->fails())
            return $this->error($validacion->errors(), 422);
        
        $user = User::where('email', $request->email)->first();
        if ($user)
            return $this->error('Usuario existente', 404);

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
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada'], 200);
    }
}
