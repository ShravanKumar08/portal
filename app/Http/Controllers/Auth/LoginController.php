<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use Auth;
use Cookie;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';
    protected $access_domains = ['technokryon.com'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        $last_logged = $this->_get_last_logged_cookie();

        if (\request()->has('new') || !$last_logged) {
            $this->_delete_last_logged_cookie();
            $view = 'auth.login';
        } else if($last_logged){
            \Session::put('url.intended', \URL::previous());
            $view = 'auth.lock';
        }

        return view($view, compact('last_logged'));
    }

    protected function credentials(Request $request) {
        return $request->only($this->username(), 'password') + ['active' => 1];
    }

    protected function authenticated(Request $request, $user) {
        $this->_add_last_logged_cookie($user);
        $request->session()->forget('SETTINGS.THEME_COLOR');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath() {
        if (auth()->user()->hasRole('employee')) {
            return 'employee/dashboard';
        }
        
        if (auth()->user()->hasRole('trainee')) {
            return 'trainee/dashboard';
        }

        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider) {

        $driver = Socialite::driver($provider);

        if ($provider == 'google') {
//            $driver->with(['approval_prompt' => 'none']);
        }

        return $driver->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider) {

        $user = Socialite::driver($provider)->user();

        $domain = AppHelper::getDomainFromEmail($user->email);

        if (in_array($domain, $this->access_domains)) {
            $model = User::where('email', $user->email)->first();

            if ($model) {
                Auth::loginUsingId($model->id);
                $this->_add_last_logged_cookie($user);
                return redirect($this->redirectPath());
            } else {
                flash('Your account not yet processed. contact admin for more details.')->error();
            }
        } else {
            flash('Access denied. Invaild email domain.')->error();
        }

        return redirect('login');
    }

    public function logout(Request $request) {
        $this->guard()->logout();
        $request->session()->invalidate();
        $this->_delete_last_logged_cookie();

        return redirect('/login');
    }

    private function _get_last_logged_cookie() {
        $last_logged = Cookie::get('last_logged_email');

        if ($last_logged) {
            return User::where('email', $last_logged)->first();
        }

        return false;
    }

    private function _add_last_logged_cookie($user) {
        $minutes = env('LAST_LOGGED_COOKIE_MINUTES', 1440);

        Cookie::queue('last_logged_email', $user->email, $minutes);
        Cookie::queue('last_logged_name', $user->name, $minutes);
    }

    private function _delete_last_logged_cookie() {
        Cookie::queue(Cookie::forget('last_logged_email'));
        Cookie::queue(Cookie::forget('last_logged_name'));
    }

}
