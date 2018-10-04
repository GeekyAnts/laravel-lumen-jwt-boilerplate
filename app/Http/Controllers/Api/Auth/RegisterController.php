<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Contracts\AuthRepository;
use App\Http\Controllers\Controller;

/**
 * @Resource("")
 */
class RegisterController extends Controller
{
	/**
	 * Contract for Auth Controller
	 * 
	 * @var \App\Contracts\AuthRepository
	 */
	protected $contract;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $contract)
    {
    	$this->contract = $contract;
    }

    /**
     * Register Or Create a new User
     *
     * @POST("register")
     * @Transaction({
     *     @Request({
     *         "first_name": "Foo",
     *         "email": "foo@foo.com",
     *         "password": "foobar"
     *     }),
     *     @Response(200, body={"success": "TRUE", "data": {"message": "Message!"}}),
     *     @Response(401, body={"success": "FALSE", "error": {}})
     * })
     */
    public function register(Request $request)
    {
    	$credentials = request([
    		'name',
    		'email',
    		'password'
    	]);

    	$rules = [
    		'name' => 'required|max:255',
    		'email' => 'required|email|max:255|unique:users'
    	];

    	$validator = Validator::make($credentials, $rules);
        if ($validator->fails() ) {
            return response()->json([
            	'success' => false, 
            	'error' => $validator->messages()
            ], 401);
        }

        $user = $this->contract->create($credentials);

        $this->contract->doAsyncVerification($user);

        return response()->json([
        	'success' => true,
        	'data' => [
                'message' => 'Thanks for signing up! Please check your e-mail to complete your registration.'
            ]
        ]);
    }
}
