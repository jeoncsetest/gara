<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use Log;
use Illuminate\Http\RedirectResponse;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /**
         * check if the user is a SIMPLE user
         */
        if (Auth::check()) {
            $account = Account::find(Auth::user()->account_id);      
            $session_usr_name = $request->session()->get('name');
            Log::debug('session_usr_name :' .$session_usr_name);
            
            if ($account->account_type == config('attendize.simple_account_type') || 
            $account->account_type == config('attendize.ticket_account_type') || 
            ($account->account_type != config('attendize.default_account_type')  && empty($session_usr_name))) {
              return new RedirectResponse(route('showEventListPage'));
            }
        }

        if (Auth::guard($guard)->guest()) {
            if ($request->is('api/*') || $request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }

            
        }
        return $next($request);
    }
}