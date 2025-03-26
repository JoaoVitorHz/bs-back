<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use  App\Http\Controllers\DoctorController;
use  App\Http\Controllers\ClinicController;

// use App\Models\User;
// use App\Models\Doctor;
// use App\Models\Addresses;
// use App\Models\MedicalFormAnswered;


class AuthController extends Controller
{
    public $rules = [
        'name' => ['required', 'string', 'max:255', 'regex:/^[^\d]+$/'], // Adicionando a regex para evitar números
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:3'],
    ];
    
    public $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.regex' => 'O nome não pode conter numeros',

        'email.required' => 'O e-mail é obrigatório.',
        'email.unique' => 'E-mail já cadastrado no sistema.',
        'email.email' => 'Por favor, insira um e-mail válido.',

        'password.required' => 'Por favor, insira uma senha',
        'password.min' => 'Por favor, pelo menos dois digitos na senha',
    ];

    public function unauthorized(){
        return response()->json([
            'error' => 'Não autorizado'
        ], 401);
    }
    public function register(Request $request){
        $validator = Validator::make( $request->all(), $this->rules, $this->messages );

        if ($validator->fails()) {
            return response()->json( [ 'errors' => $validator->errors()->first() ], 400);
        }    

        $array = ['error' => ''];

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => password_hash($request['password'], PASSWORD_DEFAULT)
        ]);
        $array['user'] = $user;
   
        $token = auth()->attempt([
            'email' => $request['email'],
            'password' => $request['password']
        ]);
        $array['token'] = $token;
        
        //pega o usuario com base no hash
        $teste = auth()->user();
        $array['teste'] = $teste;

        return $array;
    }

    public function Login(Request $request){
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:3'],
        ];

        $messages = [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'password.required' => 'Por favor, insira uma senha',
        ];

        $validator = Validator::make( $request->all(), $rules, $messages );
        if ($validator->fails()) {
            return response()->json( [ 'errors' => $validator->errors()->first() ], 400);
        }

        $token = auth()->attempt([
            'email' => $request['email'],
            'password' => $request['password']
        ]);

        if(!$token){
            return response()->json(['errors' => 'Email ou senha invalida'], 401);
        }
        
        $array['token'] = $token;
        
        $user = auth()->user();
        $array['user'] = $user;

        return $array;
    }

}
