<?php

namespace App\Http\Controllers;

use App\Attendize\Utils;
use App\Models\Affiliate;
use App\Models\Event;
use App\Models\EventAccessCodes;
use App\Models\EventStats;
use App\Models\Account;
use App\Models\User;
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
        Log::debug('logged in');
        if (empty(Auth::user())) {
            Log::debug('not logged in');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }
        }
        
        $user = User::find(Auth::user()->id);
        $data = [
            'user' => $user,
            'is_embedded' => 0
        ];
        return view ('Public.ViewEvent.ProfileMenu', $data);
    }


}
