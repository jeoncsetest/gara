<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Redirect;
use View;
use Auth;
use Log;
use Cart;
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
            if($account->is_banned){
                Log::debug('utente blocccato:' .Auth::user()->first_name .' ' . Auth::user()->last_name );
                $this->auth->logout();
                Session::flush();
                return response('Unauthorized.', 401);
            }
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }
        }
        Cart::restore(Auth::user()->id);
        Cart::store(Auth::user()->id);
        if(Cart::count()>0){
            $request->session()->put('current_event_id', Cart::content()->first()->options->event_id);
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
        $ajaxCall = $request->get('ajaxCall');
        Log::debug('postSimpleLogin with param ajaxcall:' .$ajaxCall );
        if (empty($email) || empty($password)) {

            if (!empty($ajaxCall)) {
                return response()->json([
                    'message' => trans("Controllers.fill_email_and_password"),
                     'failed' => 'true',
                ]);
            }else{
                return Redirect::back()
                ->with(['message' => trans("Controllers.fill_email_and_password"), 'failed' => true])
                ->withInput();
            }
          
        }

        if ($this->auth->attempt(['email' => $email, 'password' => $password], true) === false) {
            if (!empty($ajaxCall)) {
                return response()->json([
                    'message' => trans("Controllers.login_password_incorrect"),
                     'failed' => 'true',
                ]);
            }else{
                return Redirect::back()
                ->with(['message' => trans("Controllers.login_password_incorrect"), 'failed' => true])
                ->withInput();
            }
        }

        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if($account->is_banned){
                Log::debug('utente blocccato:' .Auth::user()->first_name .' ' . Auth::user()->last_name );
                $this->auth->logout();
                Session::flush();
                return response('Unauthorized.', 401);
            }
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }
        }
        Cart::restore(Auth::user()->id);
        Cart::store(Auth::user()->id);
        Log::debug('restored cart count :' . Cart::count());
        if(Cart::count()>0){
            $request->session()->put('current_event_id', Cart::content()->first()->options->event_id);
        }
        session()->put('name', Auth::user()->first_name );
        session()->put('surname', Auth::user()->last_name);
        session()->put('account_type', $account->account_type);
        $school = Auth::user()->school;
        if(!empty($school)){
            session()->put('school', $school->name);
        }
        Log::debug('login successful');

        if (!empty($ajaxCall)) {
            return response()->json([
                'status'      => 'success',
                'message'      => 'suc',
                'redirectUrl' => route('homepage'),
            ]);
        }else{
            return new RedirectResponse(route('homepage'));
        }        
    }

    public function loginWithLogoutSimple(Request $request)
    {
        $this->auth->logout();
        Session::flush();
        $email = $request->get('email');
        $password = $request->get('password');
        $ajaxCall = $request->get('ajaxCall');
        Log::debug('loginWithLogoutSimple->login successful. ajaxcall:' .$ajaxCall );
        if (empty($email) || empty($password)) {

            if (!empty($ajaxCall)) {
                return response()->json([
                    'message' => trans("Controllers.fill_email_and_password"),
                     'failed' => 'true',
                ]);
            }else{
                return Redirect::back()
                ->with(['message' => trans("Controllers.fill_email_and_password"), 'failed' => true])
                ->withInput();
            }
          
        }

        if ($this->auth->attempt(['email' => $email, 'password' => $password], true) === false) {
            if (!empty($ajaxCall)) {
                return response()->json([
                    'message' => trans("Controllers.login_password_incorrect"),
                     'failed' => 'true',
                ]);
            }else{
                return Redirect::back()
                ->with(['message' => trans("Controllers.login_password_incorrect"), 'failed' => true])
                ->withInput();
            }
        }

        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if($account->is_banned){
                Log::debug('utente blocccato:' .Auth::user()->first_name .' ' . Auth::user()->last_name );
                $this->auth->logout();
                Session::flush();
                return response()->json([
                    'status'      => 'error',
                    'message'      => "L'utente è stato bloccato",
                ]);
            }
            if ($account->account_type == config('attendize.default_account_type')) {
                if (!empty($ajaxCall)) {
                    Log::debug('login successful');
                    return response()->json([
                        'status'      => 'success',
                        'message'      => 'suc',
                        'redirectUrl' => route('showSelectOrganiser'),
                    ]);
                }else{
                    return redirect()->route('showSelectOrganiser');
                }
                
            }
        }
        Cart::restore(Auth::user()->id);
        Cart::store(Auth::user()->id);
        Log::debug('restored cart count :' . Cart::count());
        if(Cart::count()>0){
            $request->session()->put('current_event_id', Cart::content()->first()->options->event_id);
        }
        session()->put('name', Auth::user()->first_name );
        session()->put('surname', Auth::user()->last_name);
        session()->put('account_type', $account->account_type);
        $school = Auth::user()->school;
        if(!empty($school)){
            session()->put('school', $school->name);
        }
        Log::debug('login successful');

        if (!empty($ajaxCall)) {
            return response()->json([
                'status'      => 'success',
                'message'      => 'suc',
                'redirectUrl' => route('showEventListPage'),
            ]);
        }else{
            return new RedirectResponse(route('showEventListPage'));
        }    
    }
}
