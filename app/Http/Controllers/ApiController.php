<?php

/**
 * ApiController
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

class ApiController extends Controller
{
    
    /**
	 * generate()
	 * Method for generate token api for user
	 *
	 * @param @Illuminate\Http\Request 	$request 	 	
	 *
	 * @return json
	 */
	public function generate(Request $request) 
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
