<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
 
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function login(Request $request){
        /*
        $input = $request->only('email', 'password');
        $jwt_token = null;
 
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);   
        */

        $credentials = request(['email', 'password']);
        $token = null;
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    
    public function logout(Request $request){
        /*
        JWTAuth::invalidate($request->token);
        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
        */
        auth('api')->logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        
        ]);
        
    }
    
    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'user_name'=>$user->nome,
            'user_email'=>$user->email,
            'success' => true,
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }

}