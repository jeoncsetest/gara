<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Log;

class UserLogoutController extends Controller
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Log a user out and redirect them
     *
     * @return mixed
     */
    public function doLogout()
    {
        $this->auth->logout();
        Session::flush();
        return redirect()->to('/homepage');
    }

    
    /**
     * Log a user out and redirect them
     *
     * @return mixed
     */
    public function doLogoutSimple(Request $request)
    {
        $this->auth->logout();
        Session::flush();
        $ajaxCall = $request->get('ajaxCall');
        Log::debug('logout successful. ajaxcall:' .$ajaxCall );
        if (!empty($ajaxCall)) {
            Log::debug('return ajax respon logout');
            return response()->json([
                'message' => trans("logout success"),
                 'status' => 'success',
            ]);
        }else{
            return redirect()->to('/eventList?logged_out=yup');
        }
        
    }
}
