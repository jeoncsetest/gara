<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Redirect;
use View;
use Auth;
use App\Models\Account;

class UserLoginController extends Controller
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest');
    }

    /**
     * Shows login form.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function showLogin(Request $request)
    {
        /*
         * If there's an ajax request to the login page assume the person has been
         * logged out and redirect them to the login page
         */
        if ($request->ajax()) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('login'),
            ]);
        }

        return View::make('Public.LoginAndRegister.Login');
    }

      /**
     * Shows login form.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function showSimpleLogin(Request $request)
    {
        /*
         * If there's an ajax request to the login page assume the person has been
         * logged out and redirect them to the login page
         */
        
        if ($request->ajax()) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('loginSimple'),
            ]);
        }

        return View::make('Public.LoginAndRegister.LoginSimple');
    }


    /**
     * Handles the login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if (empty($email) || empty($password)) {
            return Redirect::back()
                ->with(['message' => trans("Controllers.fill_email_and_password"), 'failed' => true])
                ->withInput();
        }

        if ($this->auth->attempt(['email' => $email, 'password' => $password], true) === false) {
            return Redirect::back()
                ->with(['message' => trans("Controllers.login_password_incorrect"), 'failed' => true])
                ->withInput();
        }
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }
        }
        session()->put('name', Auth::user()->first_name );
        session()->put('surname', Auth::user()->last_name);
        session()->put('account_type', $account->account_type);
        return redirect()->intended(route('showSelectOrganiser'));
    }
    public function postSimpleLogin(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if (empty($email) || empty($password)) {
            return Redirect::back()
                ->with(['message' => trans("Controllers.fill_email_and_password"), 'failed' => true])
                ->withInput();
        }

        if ($this->auth->attempt(['email' => $email, 'password' => $password], true) === false) {
            return Redirect::back()
                ->with(['message' => trans("Controllers.login_password_incorre"), 'failed' => true])
                ->withInput();
        }

        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }
        }
        session()->put('name', Auth::user()->first_name );
        session()->put('surname', Auth::user()->last_name);
        session()->put('account_type', $account->account_type);
        return new RedirectResponse(route('showEventListPage'));
    }
}
