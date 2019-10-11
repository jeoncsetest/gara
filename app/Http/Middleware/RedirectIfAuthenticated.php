<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Auth;
use App\Models\Account;
use Log;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
            if ($this->auth->check()) {
              return new RedirectResponse(route('showSelectOrganiser'));
        }
        */
        /* modification to utilize the same user aithentication used by attendize in front end */
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if ($this->auth->check()) {
            $account = Account::find(Auth::user()->account_id);
           
            if ($account->account_type == config('attendize.simple_account_type') || 
                $account->account_type == config('attendize.ticket_account_type')){
              return new RedirectResponse(route('showEventListPage'));
            }else{
              return new RedirectResponse(route('showSelectOrganiser'));
            }
        }

        return $next($request);
    }
}
