<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @Resource("")
 */
class TokenController extends Controller
{
    /**
     * Log-out the user (Invalidate the token).
     *
     * @POST("logout")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT-Token>"}),
     *     @Response(200, body={"success": "TRUE", "data": {"message": "Successfully logged out"}})
     * })
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true, 
            'data' => [
                'message' => 'Successfully logged out'
            ]
        ]);
    }

    /**
     * Refresh a token.
     *
     * @POST("refresh")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT-Token>"}),
     *     @Response(200, body={
     *         "success": "TRUE", 
     *         "data": {
     *             "message": "Your token has been successfully refreshed now!",
     *             "token": "<JWT-Token>",
     *             "expires_in": 0
     *         }
     *     })
     * })
     */
    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Your token has been successfully refreshed now!',
                'token' => $token,
                'expires_in' => Auth::factory()->getTTL() * 60
            ]
        ]);
    }

    public function userVerify(Request $request, $token)
    {
        $user = User::whereConfirmationCode($token)->first();
        if (! $user) {
            return view('pages.show-message', ['error_message' => 'Invalid Link Followed!']);
        }

        $user->confirmation_code = NULL;
        $user->confirmed_at = date('Y-m-d H:i:s');
        $user->save();

        return view('pages.show-message', ['success_message' => 'Your account has been successfully activated now!']);
    }
}
