<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

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
    public function doLogoutSimple()
    {
        $this->auth->logout();
        Session::flush();
        return redirect()->to('/eventList?logged_out=yup');
    }
}
