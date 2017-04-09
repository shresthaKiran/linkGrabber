<?php

namespace App\Http\Controllers;

use App\core\Validation\Auth\User;
use App\Services\Family\Family;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use App\Services\User\User as UserManager;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\Facades\FormBuilder;

class AuthController extends Controller
{
    use ThrottlesLogins;

    protected $redirectTo = '/';

    protected $redirecPath = 'dashboard';

    protected $redirectAfterLogout = '/';

    protected $formBuilder;

    protected $userManager;

    protected $familyManager;

    /**
     * Create a new authentication controller instance.
     *
     * @param FormBuilder $formBuilder
     * @param UserManager $userManager
     * @param Family      $familyManager
     */
    public function __construct(FormBuilder $formBuilder, UserManager $userManager, Family $familyManager)
    {
        $this->middleware('auth', ['except' => 'logout']);
        $this->formBuilder   = $formBuilder;
        $this->userManager   = $userManager;
        $this->familyManager = $familyManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function getLoginForm()
    {
        if (Auth::check()) {
            redirect()->to($this->redirecPath);
        }
        $loginFormPath = 'App\Core\Forms\Auth\Login';

        $form = $this->formBuilder->create(
            $loginFormPath,
            [
                'method' => 'POST',
                'url'    => route('auth.login'),
                'class'  => 'login-form'
            ]
        );

        return view('auth.login', compact('form'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function getRegistrationForm()
    {
        $registrationFormPath = 'App\Core\Forms\Auth\RegisterFamily';

        $form = $this->formBuilder->create(
            $registrationFormPath,
            [
                'method' => 'POST',
                'url'    => route('auth.register'),
                'class'  => 'login-form'
            ]
        );

        return view('auth.register-family', compact('form'));
    }

    /**
     * @param Registration $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function postRegistration(Registration $request)
    {
        session(['family_name' => $request->get('family_name')]);


        return redirect()->route('auth.register.user');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function registerUser()
    {
        $registerUserFormPath = 'App\Core\Forms\Auth\Registration';

        $form = $this->formBuilder->create(
            $registerUserFormPath,
            [
                'method' => 'POST',
                'url'    => route('auth.register-user'),
                'class'  => 'login-form'
            ]
        );

        return view('auth.register', compact('form'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param User $request
     * @return User
     * @internal param array $data
     */
    protected function store(User $request)
    {
        $family = $this->familyManager->store(session('family_name'));
        $user   = $this->userManager->store($request->all(), $family->id);

        return redirect()->to('/');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        return $this->login($request);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials)) {
            if ($throttles) {
                $this->clearLoginAttempts($request);
            }

            return redirect()->to($this->redirecPath);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && !$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'username' => 'required',
                'password' => 'required',
            ]
        );
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {

        return redirect()->back()
                         ->withInput($request->only($this->loginUsername(), 'remember'))
                         ->withErrors(
                             [
                                 'username' => $this->getFailedLoginMessage(),
                                 'password' => $this->getFailedLoginMessage(),
                             ]
                         );
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? Lang::get('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('username', 'password');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        return $this->logout();
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->to($this->redirectAfterLogout);
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class,
            class_uses_recursive(static::class)
        );
    }
}
