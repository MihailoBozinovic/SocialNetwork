<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/v1')->group(function () {
    Route::get('/user/{id}', function (int $id) {
        return User::findOrFail($id);
    });
    Route::get('/users', function () {
        return User::all();
    });

    Route::post('/update-email/{id}', function (int $id, Request $request) {
        if ($user = User::find($id)) {
            $user->email = $request->email;
            try {
                $user->save();
                return response()->json([
                    'code' => 200,
                    'message' => "Email changed successfully!"
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Email is already in use!'
                ]);
            }
        } else {
            return response()->json([
                'code' => 400,
                'message' => "User not found!"
            ]);
        }
    });

    Route::post('/login', function (Request $request) {
        $user = User::where('email', $request->input)->orWhere('username', $request->input)->firstOrFail();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = Token::where('id_user', $user->id)->firstOrFail();
                Session::put('token', $token);
                return response()->json([
                    'code' => 200,
                    'message' => "Succesfull login!",
                    'token' => $token->token
                ]);
            } else {
                return response()->json([
                    'code' => 400,
                    'message' => "Wrong password!"
                ]);
            }
        } else {
            return response()->json([
                'code' => 400,
                'message' => "User not found!"
            ]);
        }
    });
});
