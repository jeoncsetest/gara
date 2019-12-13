<?php

namespace App\Http\Controllers;

use App\Attendize\Utils;
use App\Models\Affiliate;
use App\Models\Event;
use App\Models\EventAccessCodes;
use App\Models\EventStats;
use App\Models\Student;
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
use App\Models\Account;
use Illuminate\Support\Facades\Session;


class SchoolManagementController extends Controller
{
    /**
     * Show the homepage for subscritpion
     *
     * @param Request $request
     * @param $event_id
     * @param string $subs_slug
     * @param bool $preview
     * @return mixed
     */
    public function showStudentsPage(Request $request, $event_id, $slug = '', $preview = false)
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
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }

        $user = Auth::user();
        $school = Auth::user()->school;
        if(empty($school)){
            Log::debug('only a school can add student with this module');
            /* return new RedirectResponse(route('loginSimple'));*/
             return redirect()->to('/loginSimple');
        }
       
        $data = [
            'students' => $school->students,
            'is_embedded' => 0
        ];

        Log::debug('has school');
        return view('Public.ViewEvent.StudentsPage', $data);

/*        $signupType = $request->get('signupType');
        Log::debug('simple lìsign up:' . $signupType);
        return view('Public.ViewEvent.addStudent', compact('is_attendize', 'signupType'));
        
        */
    }


    public function showAddStudent(Request $request)
    {
        if (empty(Auth::user())) {
            Log::debug('not logged in');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }

        $user = Auth::user();
        $school = Auth::user()->school;
        if(empty($school)){
            Log::debug('only a school can add student with this module');
            /* return new RedirectResponse(route('loginSimple'));*/
             return redirect()->to('/loginSimple');
        }
        Log::debug('loggen in as school');
        $is_attendize = Utils::isAttendize();
        $signupType = $request->get('signupType');
        Log::debug('simple lìsign up:' . $signupType);
        return view('Public.ViewEvent.addStudent', compact('is_attendize', 'signupType'));
    }

    /**
     * add a student for a school.
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postAddStudent(Request $request)
    {
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }

        $user = Auth::user();
        $school = Auth::user()->school;
        if(empty($school)){
            Log::debug('only a school can add student with this module');
            /* return new RedirectResponse(route('loginSimple'));*/
             return redirect()->to('/loginSimple');
        }

        $is_attendize = Utils::isAttendize();
        $signupType = $request->get('signupType');
        $validation_rules_signup_student = [];
        $validation_rules_signup_school = [];
        $validation_rules_signup = [
           /* 'email'        => 'required|email|unique:users',*/
            'first_name'   => 'required',
            'fiscal_code'   => 'required',
            /*'phone'   => 'required',*/
            'birth_date'   => 'required',
            'birth_place'   => 'required',
            'terms_agreed' => $is_attendize ? 'required' : '',
        ];

        $validation_rules_signup = $validation_rules_signup + $validation_rules_signup_student ;
        $this->validate($request, $validation_rules_signup);


        DB::beginTransaction();

        try { 
            $student = new Student();
            $student_data = $request->only(['email', 'phone', 
            'fiscal_code', 'birth_date', 'birth_place']);
            $student_data['school_eps'] =$school->eps;
            $student_data['name'] = $request->get('first_name');
            $student_data['surname'] = $request->get('last_name');
            $student = Student::create($student_data);
    } catch (Exception $e) {

        Log::error('error in signup ' . $e);
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Whoops! There was a problem processing your order. Please try again.'
        ]);

    }
    //save the order to the database
    DB::commit();

        session()->flash('message', 'Success! You can now login.');

        return redirect('showStudentsPage');

       
    }


    /**
     * add a student for a school.
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function postAddBallerino(Request $request)
    {
        Log::debug('postAddBallerino');
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }

        $user = Auth::user();
        $school = Auth::user()->school;
        if(!empty($school)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Whoops! solo un ballerino può aggiungere un altro ballerino.'
            ]);
        }
        Log::debug('aggiungi ballerino');
        $is_attendize = Utils::isAttendize();
        $validation_rules_signup_student = [];
        $validation_rules_signup_school = [];
        $validation_rules_signup = [
            'name'        => 'required',
            'surname'   => 'required',
            'fiscal_code'   => 'required',
            'birth_date'   => 'required',
            'birth_place'   => 'required',
            'terms_agreed' => $is_attendize ? 'required' : '',
        ];

        $validation_rules_signup = $validation_rules_signup + $validation_rules_signup_student ;
        $this->validate($request, $validation_rules_signup);

        $schools = DB::table('schools')
        ->where('eps', '=', 'zxcv1234')
        ->get();
        if(empty($schools) || $schools->count()==0){
            return response()->json([
                'status'  => 'error',
                'message' => 'Whoops! There was a problem processing your signup. Please try again.'
            ]);
        }
        $rowIdCart = $request->get('item_rowId');

        if(empty($rowIdCart)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Sì è verificato un errore',
            ]);
    
        }

        $cart = Cart::get($rowIdCart);
        if(empty($cart)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Sì è verificato un errore',
            ]);
    
        }

        $data = json_decode($cart->name, true);
        Log::debug($data);

        
        $type = $cart->options->has('type') ? $cart->options->type : '';
        Log::debug('type : '.$type);
        Log::debug('count:' .count($data['participants']));
        if(($type == 'S' && count($data['participants'])>0) || ($type == 'D' && count($data['participants']) >2)){
            return response()->json([
                'status'  => 'error',
                'message' => 'non puoì aggiungere altro ballerino per questa gara',
            ]);
        }        
        $type = $cart->options->has('type') ? $cart->options->type : '';
        Log::debug('type : '.$type);
        Log::debug('count:' .count($data['participants']));
        if(($type == 'S' && count($data['participants'])>0) || ($type == 'D' && count($data['participants']) >2)){
            return response()->json([
                'status'  => 'error',
                'message' => 'non puoì aggiungere altro ballerino per questa gara',
            ]);
        }
        $students = DB::table('students')
                ->where('fiscal_code', '=', $request->get('fiscal_code'))
                ->get();
        if(!empty($students) && $students->count() >0){
            return response()->json([
                'status'  => 'error',
                'studentExists' => 'true',
                'studentId' => $students[0]->id,
                'studentName' => $students[0]->name,
                'studentSurname' => $students[0]->surname,
                'message' => 'Whoops! un ballerino con stesso codice fiscale' . $students[0]->fiscal_code . 'presente già, voui aggiungere comunque?'
            ]);
        }

        DB::beginTransaction();
        $student = null;
        try { 
            $student = new Student();
            $student_data = $request->only(['name', 'surname', 
            'fiscal_code', 'birth_date', 'birth_place']);
            $student_data['school_eps'] = $schools->first()->eps;
            $student = Student::create($student_data);
    } catch (Exception $e) {

        Log::error('error in signup ' . $e);
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Whoops! There was a problem processing your order. Please try again.'
        ]);

    }
    //save the order to the database
    DB::commit();

    session()->flash('message', 'Success! You can now login.');
    if(empty($student)){
        return response()->json([
            'status'  => 'error',
            'message' => 'Whoops! There was a problem processing your order. Please try again.'
        ]);
    }else{
        return response()->json([
            'status'  => 'success',
            'message' => 'The student has been added successfully.',
            'student_id' => $student->id,
            'studentId' => $student->id,
            'studentName' => $student->name,
            'studentSurname' => $student->surname,
        ]);
    }     
    }
}
