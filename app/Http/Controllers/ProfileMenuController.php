<?php

namespace App\Http\Controllers;

use App\Attendize\Utils;
use App\Models\Affiliate;
use App\Models\Event;
use App\Models\EventAccessCodes;
use App\Models\EventStats;
use Carbon\Carbon;
use DB;
use Auth;
use Cookie;
use DateTime;
use Illuminate\Http\Request;
use Mail;
use Validator;
use Log;
use Cart;

class ProfileMenuController extends Controller
{
    //
    public function profileMenu()
    {
        return view ('Public.ViewEvent.ProfileMenu');
    }


}
