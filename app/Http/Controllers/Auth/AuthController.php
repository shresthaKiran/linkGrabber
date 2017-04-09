<?php

namespace App\Http\Controllers\Auth;

use App\core\Validation\Auth\Registration;
use App\core\Validation\Auth\User;
use App\Services\Family\Family;
use App\Services\User\User as UserManager;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $redirectPath = 'dashboard';

    protected $redirectAfterLogout = '/'

    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    /**
     * @var User
     */
    protected $userManager;
    /**
     * @var Family
     */
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
}
