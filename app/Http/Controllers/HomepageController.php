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

class HomepageController extends Controller
{
    //
    public function homepage()
    {
        return view ('public.ViewEvent.HomePage');
    }

    public function showDanceEvent()
    {
        $mytime = Carbon::now();
        $eventDay = '';
        $mytime2 = $mytime->toDateTimeString('Y-m-d H:i:s');
        $events = DB::table('events')
                ->whereDate('end_date', '>=', $mytime)
                ->Where('is_live', '=', 1)
                ->get();

                $data = [
                    'events' => $events
                ];
        foreach ($events as $e1) {
            echo $e1->title;
            echo $e1->start_date;
            $mytime2 = Carbon::createFromFormat('Y-m-d H:i:s', $e1->start_date);
            echo $mytime2->format('l');
        }

        $datetime = DateTime::createFromFormat('YmdHi', '201308131830');
        echo $datetime->format('D');
        return view('Public.ViewEvent.EventDancePage', $data);
        /*
        return view ('public.ViewEvent.EventDancePage');
        */
    }

}
