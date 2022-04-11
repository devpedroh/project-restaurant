<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;

class UserController extends Controller
{

    public function login (Request $req) {
        $data = $req->only('email', 'password');

        $userdata = DB::table('users')->where('email', $data['email'])->first();

        if ($userdata) {
            if (Auth::attempt($data)) {
                $user = Auth::user();
                $user->token = $user->createToken($user->email)->accessToken;

                return [
                    'message' => 'Autenticado com sucesso!',
                    'user' => $user,
                    'status' => 200
                ];
            } else {
                return [
                    'message' => 'Email ou senha inválido!',
                    'status' => 400
                ];
            }
        } else {
            return [
                'message' => 'Email não registrado!',
                'status' => 400
            ];
        }
    }

    public function logout (Request $req) {

        if (Auth::check()) {
            $req->user()->token()->revoke();

            return [
                'message' => 'Sessão encerrada!',
                'status' => 200
            ];
        }

        return [
            'message' => 'Não autenticado!',
            'status' => 403
        ];
    }

}
