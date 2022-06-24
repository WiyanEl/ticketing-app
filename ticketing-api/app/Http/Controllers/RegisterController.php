<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'username' => ['required', 'unique:users'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $response = [
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'data' => $user
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $err) {
            return response()->json([
                'message' => 'Failed' . $err->errorInfo
            ]);
        }
    }
}
