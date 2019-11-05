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

class DescriptionsOrderController extends Controller
{
    //
    public function descriptionOrders()
    {
        $orders = DB::table('orders')
        ->where('orders.user_id', '=', Auth::user()->id)
        ->get();
        Log::debug('total:' . $orders->count());
        return view('Public.ViewEvent.DescriptionsOrder', [
        'orders' => $orders,
        ]);
    }


}
