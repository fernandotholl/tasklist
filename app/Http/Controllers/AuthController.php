<?php

/**
 * AuthController
 *
 * @author Fernando Tholl <contato@fernandotholl.net>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Hash;

class AuthController extends Controller
{

	/**
	 * authenticate()
	 *
	 * @param @Illuminate\Http\Request 	$request 	 	
	 * @return string
	 */
    public function authenticate(Request $request) 
    {

      	$credentials = $request->only('username', 'password');
      	$user = User::where('username', $credentials['username'])->first();

      	if(!$user) {
        	return response()->json([
          		'error' => 'Invalid credentials'
        	], 401);
      	}

      	if (!Hash::check($credentials['password'], $user->password)) {
        	return response()->json([
            	'error' => 'Invalid credentials'
          	], 401);
      	}

      	$token = JWTAuth::fromUser($user);
      
      	return response()->json([
        	'access_token' => $token
      	]);
    }
}
