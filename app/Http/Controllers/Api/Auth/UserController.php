<?php

namespace App\Http\Controllers\Api\Auth;

use Storage, Validator;

use Illuminate\Http\Request;

use App\Contracts\AuthRepository;
use App\Http\Controllers\Controller;

/**
 * @Resource("")
 */
class UserController extends Controller
{
    protected $user;

    /**
     * Create new instance of UserController
     * 
     * @return void
     */
    public function __construct()
    {
        $this->user = auth('api')->user();
    }
    
    /**
     * Get the authenticated User.
     */
    public function me(Request $request)
    {
        $user = $this->user;

        return response()->json([
            'success' => true, 
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Update the authenticated User.
     */
    public function putMe(Request $request, AuthRepository $contract)
    {
        $user = $this->user;

        $credentials = request([
    		'name'
    	]);

    	$rules = [
    		'name' => 'required|max:255'
    	];

    	$validator = Validator::make($credentials, $rules);
        if ($validator->fails() ) {

            return response()->json([
            	'success' => false, 
            	'error' => $validator->messages()
            ], 401);
        }

        $updatedUser = $contract->update($user, $credentials);
        
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Details has been successfully updated!',
                'user' => $updatedUser
            ]
        ]);
    }
}
