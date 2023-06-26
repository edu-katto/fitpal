<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Requests\V1\UserLogoutRequest;
use Illuminate\Http\Request;
use App\Http\Requests\V1\UserRegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class AuthController extends Controller
{
    //Función que utilizaremos para registrar al usuario
    public function register(UserRegisterRequest $request): object
    {

        //Creamos el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //Nos guardamos el usuario y la contraseña para realizar la petición de token a JWTAuth
        $credentials = $request->only('email', 'password');

        //Devolvemos la respuesta con el token del usuario
        return response()->json([
            'message' => 'User created',
            'token' => JWTAuth::attempt($credentials),
            'user' => $user
        ], Response::HTTP_OK);
    }

    //Funcion que utilizaremos para hacer login
    public function authenticate(UserLoginRequest $request): object
    {
        //Indicamos que solo queremos recibir email y password de la request
        $credentials = $request->only('email', 'password');

        //Intentamos hacer login
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                //Credenciales incorrectas.
                return response()->json([
                    'message' => 'Login failed',
                ], 401);
            }
        } catch (JWTException $e) {
            //Error servidor
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
        //Devolvemos el token
        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ]);
    }
    //Función que utilizaremos para eliminar el token y desconectar al usuario
    public function logout(UserLogoutRequest $request): object
    {
        try {
            //Si el token es valido eliminamos el token desconectando al usuario.
            JWTAuth::invalidate($request->token);
            return response()->json([
                'success' => true,
                'message' => 'User disconnected'
            ]);
        } catch (JWTException $exception) {
            //Error servidor
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
