<?php

namespace Wink\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('wink::login');
    }

    /**
     * Attempt to log in.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        validator(request()->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->guard()->attempt(
            request()->only('email', 'password'),
            request()->filled('remember')
        )) {
            return redirect('/'.config('wink.path'));
        }

        throw ValidationException::withMessages([
            'email' => ['Invalid email or password!'],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('wink.auth.login')->with('loggedOut', true);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('wink');
    }
}
