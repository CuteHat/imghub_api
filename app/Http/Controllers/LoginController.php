<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->except(('_token'));

        if (Auth::attempt($credentials)){
            return redirect()->route("my_posts");
        }
        else {
            abort(403);
        }
    }

    public function register()
    {
        return view('users.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect('/users/login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route("home");
    }
}
