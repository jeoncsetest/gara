<?php

namespace App\Http\Controllers;

use App\Attendize\Utils;
use App\Models\Account;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Hash;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Mail;
use Log;
use DB;
use Auth;

class UserSignupController extends Controller
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        if (Account::count() > 0 && !Utils::isAttendize()) {
            return redirect()->route('login');
        }

        $this->auth = $auth;
        $this->middleware('guest');
    }

    public function showSignupSimple(Request $request)
    {
        Log::debug('simple lìsign up');
        $is_attendize = Utils::isAttendize();
        $signupType = $request->get('signupType');
        Log::debug('simple lìsign up:' . $signupType);
        $epsList = DB::table('eps')
        ->get();
        $schools = DB::table('schools')
        ->where('eps', '!=', 'zxcv1234')
        ->get();
        return view('Public.LoginAndRegister.SignupSimple', compact('is_attendize', 'signupType', 'epsList', 'schools'));
    }

        
    public function showSignup()
    {
        $is_attendize = Utils::isAttendize();
        return view('Public.LoginAndRegister.Signup', compact('is_attendize'));
    }

    /**
     * Creates an account.
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postSignup(Request $request)
    {
        $is_attendize = Utils::isAttendize();
        $this->validate($request, [
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'first_name'   => 'required',
            'organiser_type'   => 'required',
            'terms_agreed' => $is_attendize ? 'required' : '',
        ]);

        $account_data = $request->only(['email', 'first_name', 'last_name','organiser_type']);
        Log::debug('organiser type :' . $request->get('organiser_type'));
        //$account_data['organiser_type'] = $request->get('organiser_type');
        $account_data['currency_id'] = config('attendize.default_currency');
        $account_data['timezone_id'] = config('attendize.default_timezone');
        $account_data['account_type']  = config('attendize.default_account_type');
        $account = Account::create($account_data);

        $user = new User();
        $user_data = $request->only(['email', 'first_name', 'last_name']);
        $user_data['password'] = Hash::make($request->get('password'));
        $user_data['account_id'] = $account->id;
        $user_data['is_parent'] = 1;
        $user_data['is_registered'] = 1;
        $user = User::create($user_data);

        if ($is_attendize) {
            // TODO: Do this async?
            Mail::send('Emails.ConfirmEmail',
                ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code],
                function ($message) use ($request) {
                    $message->to($request->get('email'), $request->get('first_name'))
                        ->subject(trans("Email.attendize_register"));
                });
        }

        session()->flash('message', 'Success! You can now login.');

        return redirect('login');
    }
    
    /**
     * Creates an account.
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postSignupSimple(Request $request)
    {
        $is_attendize = Utils::isAttendize();
        $signupType = $request->get('signupType');
        $validation_rules_signup_student = [];
        $validation_rules_signup_school = [];
        $validation_rules_signup = [
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'first_name'   => 'required',
            'terms_agreed' => $is_attendize ? 'required' : '',
        ];

        if($signupType == config('attendize.signup_type_student')){
            $validation_rules_signup_student =[
                'fiscal_code'   => 'required',
                'phone'   => 'required',
                'birth_date'   => 'required',
                'birth_place'   => 'required',
            ];
        }

        if($signupType == config('attendize.signup_type_school')){
            $validation_rules_signup_school =[
                'eps'   => 'required',
                'name'   => 'required|unique:schools',
                'phone'   => 'required|unique:schools',
                'city'   => 'required',
                'address'   => 'required',
                'place'   => 'required',
            ];
        }
        $validation_rules_signup = $validation_rules_signup + $validation_rules_signup_student 
        + $validation_rules_signup_school;
        $this->validate($request, $validation_rules_signup);


        DB::beginTransaction();

        try {
            $account_data = $request->only(['email', 'first_name', 'last_name']);
            $account_data['currency_id'] = config('attendize.default_currency');
            $account_data['timezone_id'] = config('attendize.default_timezone');
            /*if user want to buy only tickets, then the signup type must be TICKET and account type will be TICKET too,
            for other type of signup like students or school, account type is SIMPLE
            */
            if($signupType == config('attendize.signup_type_ticket')){
                $account_data['account_type']  = config('attendize.ticket_account_type');
            }else{
                $account_data['account_type']  = config('attendize.simple_account_type');
            }
            
            $account = Account::create($account_data);

            $user = new User();
            $user_data = $request->only(['email', 'first_name', 'last_name','phone']);
            $user_data['password'] = Hash::make($request->get('password'));
            $user_data['account_id'] = $account->id;
            $user_data['is_parent'] = 1;
            $user_data['is_registered'] = 1;
            $user = User::create($user_data);

            if($signupType == config('attendize.signup_type_student')){
                $school_eps = $request->get('school_eps');
                if(empty($school_eps)){
                    $schools = DB::table('schools')
                    ->where('eps', '=', 'zxcv1234')
                    ->get();
                    if(empty($schools) || $schools->count()==0){
                        Log::error('school not found ');
                        DB::rollBack();
                        return response()->json([
                            'status'  => 'error',
                            'message' => 'Whoops! There was a problem processing your signup. Please try again.'
                        ]);
                    }
                    $school_eps = $schools->first()->eps;
                }
               
                $student = new Student();
                $student_data = $request->only(['email', 'phone', 'school_eps',
                'fiscal_code', 'birth_date', 'birth_place']);
                $student_data['name'] = $request->get('first_name');
                $student_data['surname'] = $request->get('last_name');
                $student_data['school_eps'] = $school_eps;
                $student_data['user_id'] =$user->id;
                $student = Student::create($student_data);
            }elseif($signupType == config('attendize.signup_type_school')){
                $schools = DB::table('schools')
                ->where('eps', 'like', $request->get('eps') . '%')
                ->get();
                $eps = $request->get('eps');
                if($schools->count()>0){
                    $eps = $eps . '_' .($schools->count()+1);
                    Log::debug($request->get('eps') . ' exits, eps is changed to ' . $eps);
                }
                $school = new School();
                $school_data = $request->only(['email', 'name', 'phone', 'address', 'city', 'place']);
                $school_data['eps'] =$eps;
                $school_data['user_id'] =$user->id;
                $school = School::create($school_data);
            }

            if ($is_attendize) {
                // TODO: Do this async?
                Mail::send('Emails.ConfirmEmail',
                    ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code],
                    function ($message) use ($request) {
                        $message->to($request->get('email'), $request->get('first_name'))
                            ->subject(trans("Email.attendize_register"));
                    });
            }
    } catch (Exception $e) {

        Log::error('error in signup ' . $e);
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Whoops! There was a problem processing your signup. Please try again.'
        ]);

    }
    //save the order to the database
    DB::commit();

        session()->flash('message', 'Success! You can now login.');

        return redirect('loginSimple');
    }

    /**
     * Creates an account.
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postSignupSimpleuser(Request $request)
    {
        $is_attendize = Utils::isAttendize();
        $this->validate($request, [
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'first_name'   => 'required',
            'terms_agreed' => $is_attendize ? 'required' : '',
        ]);

        $account = Account::where('account_type', config('attendize.simple_account_type'))->first();
        if(empty($account)) {
        $account_data = $request->only(['email', 'first_name', 'last_name']);
        $account_data['currency_id'] = config('attendize.default_currency');
        $account_data['timezone_id'] = config('attendize.default_timezone');
        $account_data['account_type']  = config('attendize.simple_account_type');
        $account_data['id'] = 0;
        $account = Account::create($account_data);
        }
        $user = new User();
        $user_data = $request->only(['email', 'first_name', 'last_name']);
        $user_data['password'] = Hash::make($request->get('password'));
        $user_data['account_id'] = $account->id;
        $user_data['is_parent'] = 1;
        $user_data['is_registered'] = 1;
        $user = User::create($user_data);

        if ($is_attendize) {
            // TODO: Do this async?
            Mail::send('Emails.ConfirmEmail',
                ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code],
                function ($message) use ($request) {
                    $message->to($request->get('email'), $request->get('first_name'))
                        ->subject(trans("Email.attendize_register"));
                });
        }

        session()->flash('message', 'Success! You can now login.');

        return redirect('login');
    }

    /**
     * Confirm a user email
     *
     * @param $confirmation_code
     * @return mixed
     */
    public function confirmEmail($confirmation_code)
    {
        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user) {
            return view('Public.Errors.Generic', [
                'message' => trans("Controllers.confirmation_malformed"),
            ]);
        }

        $user->is_confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        session()->flash('message', trans("Controllers.confirmation_successful"));

        return redirect()->route('login');
    }
}
