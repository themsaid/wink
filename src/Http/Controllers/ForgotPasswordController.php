<?php

namespace Wink\Http\Controllers;

use Throwable;
use Illuminate\Support\Str;
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
        $authorModel = config('wink.models.author');

        validator(request()->all(), [
            'email' => 'required|email',
        ])->validate();

        if ($author = $authorModel::whereEmail(request('email'))->first()) {
            cache(['password.reset.'.$author->id => $token = Str::random()],
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
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function showNewPassword($token)
    {
        $authorModel = config('wink.models.author');

        try {
            $token = decrypt($token);

            [$authorId, $token] = explode('|', $token);

            $author = $authorModel::findOrFail($authorId);
        } catch (Throwable $e) {
            return redirect()->route('wink.password.forgot')->with('invalidResetToken', true);
        }

        if (cache('password.reset.'.$authorId) != $token) {
            return redirect()->route('wink.password.forgot')->with('invalidResetToken', true);
        }

        cache()->forget('password.reset.'.$authorId);

        $author->password = \Hash::make($password = Str::random());

        $author->save();

        return view('wink::reset-password', [
            'password' => $password,
        ]);
    }
}
