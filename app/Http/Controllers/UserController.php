<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(Request $request)
    {
        //falta validar información
        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        User::create($input);

        return response()->json([
            'res' => true,
            'message' => 'Add correct'
        ]);
    }

    public function login(Request $request){
        $user = User::whereEmail($request->email)->first();

        if(!is_null($user) && Hash::check($request->password, $user->password))
        {
            $user->api_token = Str::random(150);
            $user->save();

            return response()->json([
                'res' => true,
                'token' => $user->api_token,
                'message' => 'Welcome to system'
            ]);
        }
        else{
            return response()->json([
                'res' => false,
                'message' => 'Count o password no incorrect'
            ]);
        }
    }

    public function logout()
    {
        $user = auth()->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'res' => true,
            'message' => 'Good bye'
        ]);
    }
}