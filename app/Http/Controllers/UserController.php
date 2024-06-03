<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{
    public function register_post(RegistrationRequest $request){
        $requests = $request->validated();
        $requests['password'] = Hash::make($requests['password']);
        User::create($requests);
        $id = User::where('email', $requests['email'])->first();
        return response()->json(['message' => 'Аккаунт успешно создан', 'id' => $id['id']]);
    }

    public function auth_post(AuthorizationRequest $request){
        $user = User::where('email', $request['email'])->first();
        if ($user && Hash::check($request['password'], $user->password)){
            $user->makeHidden('password');
            return response()->json(['success' => true,'user'=>$user, 'message' => 'Авторизация прошла успешно.']);
        }
        else{
            return response()->json(['success' => false, 'message' => 'Неверная почта или пароль.']);
        }
    }

    public function addPoints(Request $request)
    {
        $user = User::find($request->user_id);
        $user->points += $request->points;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Points added successfully']);
    }



}
