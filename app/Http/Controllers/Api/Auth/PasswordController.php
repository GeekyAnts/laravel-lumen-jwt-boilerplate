<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;

use Illuminate\Http\Request;
use App\Contracts\AuthRepository;
use App\Http\Controllers\Controller;

/**
 * @Resource("")
 */
class PasswordController extends Controller
{
	/**
	 * Contract for Auth Controller
	 * 
	 * @var \App\Contracts\AuthRepository
	 */
	public $contract;

	/**
	 * Create a new instance of PasswordController
	 * 
	 * @return void
	 */
	public function __construct(AuthRepository $contract)
	{
		$this->contract = $contract;
	}

    /**
     * Forgot Password
     *
     * @POST("password/forgot")
     * @Transaction({
     *     @Request({"email": "foo@foo.com"}),
     *     @Response(200, body={"success": "TRUE", "data": {"message": "Message!"}})
     * })
     */
	public function forgot(Request $request)
    {
    	$credentials = $request->only(['email']);

    	$rules = [
    		'email' => 'required|email'
    	];

    	$validator = Validator::make($credentials, $rules);
    	if ($validator->fails()) {
    		return response()->json(['success' => false, 'error' => $validator->errors()], 401);
    	}

    	$this->contract->doAsyncForgotPassword($credentials);

    	return response()->json([
            'success' => true, 
            'data' => [
                'message' => 'We\'ve sent you a mail with the reset password link.'
            ]
        ]);
    }

	/**
	 * Handle the reset password "GET" request
	 * 
	 * @param \Illuminate\Http\Request
	 * @param String $token
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function reset(Request $request, $token)
    {
    	$response = $this->contract->getResetPassword($token);
    	if (! $response['success']) {
    		return abort(404);
    	}

    	return view('pages.reset-password', compact('token'));
    }

	/**
	 * Handle the reset password "POST" request
	 * 
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function postReset(Request $request)
    {
    	$credentials = $request->only(['email', 'password', 'password_confirmation', 'password_reset']);
    	
    	$rules = [
    		'email' => 'required|email',
    		'password' => 'required|min:6|max:255|confirmed',
    		'password_reset' => 'required'
    	];

    	$validator = Validator::make($credentials, $rules);
    	if ($validator->fails()) {
    		return redirect()->back()->withErrors($validator)->withInput($credentials);
    	}

        $response = $this->contract->postResetPassword($credentials);
        if (! $response['success'])
            return view('pages.show-message', ['error_messsage' => $response['message']]);
        return view('pages.show-message', ['success_message' => $response['message']]);
    }
}
