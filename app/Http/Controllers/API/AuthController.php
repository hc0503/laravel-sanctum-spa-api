<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Get token
     * @param $request
     * @return $jsonResponse
     * @route('/api/token', method='POST')
     */
    public function getToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
    
        if ($validator->fails()) {
            // $errors = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'errors' => 'The provided fields are incorrect.'
            ]);
        }

        $user = User::where('email', "$request->email")->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'error' => 'The provided credentials are incorrect.'
            ]);
        }

        return response()->json([
            'success' => true,
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * User register
     * @param $request
     * @return $jsonResponse
     * @route('/api/register', method='POST')
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $error_messages = $validator->errors()->messages();
            $error_keys = array_keys($error_messages);
            foreach ($error_keys as $error_key) {
                $error_result[$error_key] = $error_messages[$error_key];
            }
            return response()->json([
                'success' => false,
                'error' => $error_result
            ]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'The user is registered successfuly.'
        ]);
    }
}
