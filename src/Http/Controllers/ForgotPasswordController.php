<?php

namespace Wink\Http\Controllers;

use Wink\WinkAuthor;
use Wink\Mail\ResetPasswordEmail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Show the reset-password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showResetRequestForm()
    {
        return view('wink::request-password-reset');
    }

    /**
     * Send password reset email.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail()
    {
        validator(request()->all(), [
            'email' => 'required|email',
        ])->validate();

        if ($author = WinkAuthor::whereEmail(request('email'))->first()) {
            cache(['password.reset.'.$author->id => $token = str_random()],
                now()->addMinutes(30)
            );

            Mail::to($author->email)->send(new ResetPasswordEmail(
                encrypt($author->id.'|'.$token)
            ));
        }

        return redirect()->route('wink.password.forgot')->with('sent', true);
    }

    /**
     * Show the new password to the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewPassword($token)
    {
        try {
            $token = decrypt($token);

            list($authorId, $token) = explode('|', $token);

            $author = WinkAuthor::findOrFail($authorId);
        } catch (\Throwable $e) {
            return redirect()->route('wink.password.forgot')->with('invalidResetToken', true);
        }

        if (cache('password.reset.'.$authorId) != $token) {
            return redirect()->route('wink.password.forgot')->with('invalidResetToken', true);
        }

        cache()->forget('password.reset.'.$authorId);

        $author->password = bcrypt($password = str_random());

        $author->save();

        return view('wink::reset-password', [
            'password' => $password
        ]);
    }
}
