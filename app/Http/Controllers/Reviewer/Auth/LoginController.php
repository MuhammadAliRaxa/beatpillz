<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use App\Methods\ReCaptchaValidation;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reviewer Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating reviewers for the application and
    | redirecting them to your dashboard screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after reviewer login .
     *
     * @var string
     */
    public $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = reviewerPath();
        $this->middleware('guest:reviewer')->except('logout');
    }

    /**
     * Show the reviewer area login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('reviewer.auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ] + app(ReCaptchaValidation::class)->validate());
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $username = $request->input($this->username());
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return $this->guard()->attempt([
            $field => $username,
            'password' => $request->input('password'),
        ],
            $request->filled('remember')
        );
    }

    /**
     * Log the reviewer out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $sessionKey = $this->guard()->getName();

        $this->guard()->logout();

        $request->session()->forget($sessionKey);

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
        ? new JsonResponse([], 204)
        : redirect()->route('reviewer.index');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email_or_username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('reviewer');
    }
}
