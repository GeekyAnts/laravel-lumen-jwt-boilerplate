<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Controllers\Controller;

/**
 * @Resource("")
 */
class LoginController extends Controller
{
    /**
     * Login - Get a JWT via given credentials.
     *
     * @POST("login")
     * @Transaction({
     *     @Request({"email": "foo@foo.com", "password": "bar"}),
     *     @Response(200, body={
     *         "success": "TRUE", 
     *         "data": {
     *             "token": "<JWT-Token>",
     *             "expires_in": 0
     *         }
     *     }),
     *     @Response(401, body={"success": "FALSE", "error": {}})
     * })
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ], 401);
        }

        $credentials['confirmation_code'] = NULL;

        try {
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Invalid credentials or E-Mail verification is pending'
                ], 401);
            }

        } catch (JWTException $e) {
            
            return response()->json([
                'success' => false, 
                'error' => 'Invalid credentials, please try again'
            ], 500);
        }

        return response()->json([
            'success' => true, 
            'data' => [
                'token' => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}