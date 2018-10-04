<?php

namespace App\Repositories;

use DB, Hash, Mail;

use App\Models\User;

use App\Mails\{VerifyEMail, ResetPassword};

use App\Contracts\AuthRepository as AuthRepositoryContract;

class AuthRepository implements AuthRepositoryContract
{

	/**
	 * {@inheritdoc}
	 */
	public function create($credentials)
	{
		$data = [];
		$data = [
			'name' => array_get($credentials, 'name'),
			'email' => array_get($credentials, 'email'),
			'password' => Hash::make(array_get($credentials, 'password'))
		];

		$user = new User;
		
		$user->fill($data)->save();

		return $user;
	}

	/**
	 * {@inheritdoc}
	 */
	public function update($user, $credentials)
	{
		$data = [];
		$data = [
			'name' => array_get($credentials, 'name')
		];
		
		$user->fill($data)->save();

		return $user;
	}

	/**
	 * {@inheritdoc}
	 */
	public function doAsyncVerification($user)
	{
		$verificationCode = str_random(30);

		$user->confirmation_code = $verificationCode;
		$user->save();

		Mail::to(
			$user->email, 
			$user->name
		)->queue(new VerifyEMail($user->id));

		return $verificationCode;
	}

	/**
	 * {@inheritdoc}
	 */
	public function doAsyncForgotPassword($credentials)
	{
		$user = User::whereEmail($credentials['email'])->first();
		if (! $user) return false;

		$token = Hash::make($credentials['email']);

		DB::table('password_resets')->whereEmail($credentials['email'])->delete();

		DB::table('password_resets')->insert([
			'email' => $credentials['email'],
			'token' => $token,
			'created_at' => date('Y-m-d H:i:s')
		]);

		Mail::to(
			$user->email,
			$user->first_name.' '.$user->last_name
		)->queue(new ResetPassword($user->id, $token));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getResetPassword($token)
	{
		$response = ['success' => false, 'message' => 'Invalid Link Followed'];

		$object = DB::table('password_resets')->whereToken($token)->first();
		if (!$object || !Hash::check($object->email, $token)) {
			return $response;
		}

		$user = User::whereEmail($object->email)->first();
		if (! $user || $user->is_blocked == 1) {
			return $response;
		}

		$response['success'] = true;
		return $response;
	}

	/**
	 * {@inheritdoc}
	 */
	public function postResetPassword($credentials)
	{
		$response = ['success' => false, 'message' => 'Invalid Link Followed'];

		$object = DB::table('password_resets')
			->whereEmail(trim($credentials['email']))
			->whereToken(trim($credentials['password_reset']))
			->first();

		if (!$object || !Hash::check($object->email, trim($credentials['password_reset']))) {
			return $response;
		}

		$user = User::whereEmail($object->email)->first();
		if (! $user || $user->is_blocked == 1) {
			return $response;
		}

		$user->password = Hash::make($credentials['password']);
		$user->save();

		DB::statement("DELETE FROM `password_resets` WHERE `email` like '$credentials[email]' and `token` like '$credentials[password_reset]';");

		return ['success' => true, 'message' => 'We\'ve changed your account password'];
	}
}
