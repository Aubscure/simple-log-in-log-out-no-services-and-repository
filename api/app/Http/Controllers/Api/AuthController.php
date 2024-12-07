<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\CreateRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AuthenticatedUserResource;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    //
    public function authenticate(AuthRequest $request)
    {
        $email=$request->email;
        $password=$request->password;

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password))
        {
            throw ValidationException::withMessages
            (
                [
                    'email'=>['Invalid login credentials']
                ]
            );
        }

        $response = (object)
        [
            'user'=>new UserResource($user),
            'token'=>$user->createToken('auth')->plainTextToken
        ];
        return new AuthenticatedUserResource($response);
    }


    public function register(CreateRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'], // Ensure 'name' field is present and valid
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $response = (object) [
            'user' => new UserResource($user),
            'token' => $user->createToken('auth')->plainTextToken
        ];
        return new AuthenticatedUserResource($response);
    }

    public function getUsers(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'Unauthorized access - invalid token'
            ], 401);
        }

        $users = User::all();
        return UserResource::collection($users);
    }


    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message'=>'User successfully logged out',
        ],200);
    }
}
