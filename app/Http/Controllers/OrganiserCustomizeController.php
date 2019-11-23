<?php

namespace App\Http\Controllers;

use App\Models\Organiser;
use File;
use Image;
use Illuminate\Http\Request;
use Validator;
use Log;
use DB;

class OrganiserCustomizeController extends MyBaseController
{
    /**
     * Show organiser setting page
     *
     * @param $organiser_id
     * @return mixed
     */
    public function showCustomize($organiser_id)
    {
        $data = [
            'organiser' => Organiser::scope()->findOrFail($organiser_id),
        ];

        return view('ManageOrganiser.Customize', $data);
    }

   /**
     * Show all Schools
     *
     * @param Request $request
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showAllSchools(Request $request)
    {
        $allowed_sorts = ['name', 'eps', 'city'];

        $searchQuery = $request->get('q');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');

        $organiserId = $request->get('organiserId');
        Log::debug('organiser id:'.$organiserId);
        $organiser = Organiser::scope()->find($organiserId);
        $schoolList = null;
        Log::debug('$searchQuery ' .$searchQuery);
        if ($searchQuery) {
            $schoolList = DB::table('schools')
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', $searchQuery . '%')
                    ->orWhere('eps', 'like', $searchQuery . '%')
                    ->orWhere('place', 'like', $searchQuery . '%')
                    ->orWhere('city', 'like', $searchQuery . '%')
                    ->orWhere('address', 'like', $searchQuery . '%')
                    ->orWhere('email', 'like', $searchQuery . '%')
                    ->orWhere('phone', 'like', $searchQuery . '%')
                  ;
            })
            ->orderBy($sort_by, $sort_order)
            ->select('schools.*')
            ->paginate();
        } else {
            $schoolList = DB::table('schools')
            ->orderBy($sort_by, $sort_order)
            ->select('schools.*')
            ->paginate();
        }

        $data = [
            'schools'  => $schoolList,
            'organiser' =>$organiser,
            'sort_by'    => $sort_by,
            'sort_order' => $sort_order,
            'q'          => $searchQuery ? $searchQuery : '',
        ];
        return view('ManageOrganiser.AllSchools', $data);
    }


     /**
     * Show all Students
     *
     * @param Request $request
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showAllStudents(Request $request)
    {
        $allowed_sorts = ['name', 'surname', 'email', 'school_eps', 'birth_date', 'birth_place' , 'fiscal_code', 'phone'];

        $searchQuery = $request->get('q');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');

        $organiserId = $request->get('organiserId');
        $schoolEps = $request->get('school_eps');
        Log::debug('organiser id:'.$organiserId . ' $schoolEps : ' .  $schoolEps);
        $organiser = Organiser::scope()->find($organiserId);
        $studentList = null;
        Log::debug('$searchQuery ' .$searchQuery);
        if ($searchQuery) {
            $studentList = DB::table('students')
            ->where(function ($query) use ($searchQuery, $schoolEps) {
                if(empty($schoolEps)){
                    $query->where('name', 'like', $searchQuery . '%')
                    ->orWhere('surname', 'like', $searchQuery . '%')
                    ->orWhere('birth_place', 'like', $searchQuery . '%')
                    ->orWhere('birth_date', 'like', $searchQuery . '%')
                    ->orWhere('email', 'like', $searchQuery . '%')
                    ->orWhere('phone', 'like', $searchQuery . '%')
                    ->orWhere('fiscal_code', 'like', $searchQuery . '%')
                  ;
                }else{
                    $query->where('school_eps', '=', $schoolEps)
                    ->where('name', 'like', $searchQuery . '%')
                    ->orWhere('surname', 'like', $searchQuery . '%')
                    ->orWhere('birth_place', 'like', $searchQuery . '%')
                    ->orWhere('birth_date', 'like', $searchQuery . '%')
                    ->orWhere('email', 'like', $searchQuery . '%')
                    ->orWhere('phone', 'like', $searchQuery . '%')
                    ->orWhere('fiscal_code', 'like', $searchQuery . '%')
                  ;
                }
              
            })
            ->orderBy($sort_by, $sort_order)
            ->select('students.*')
            ->paginate();
        } else {
            if(empty($schoolEps)){
            $studentList = DB::table('students')
            ->orderBy($sort_by, $sort_order)
            ->select('students.*')
            ->paginate();}
            else{
                $studentList = DB::table('students')
                ->where('school_eps', '=', $schoolEps)
                ->orderBy($sort_by, $sort_order)
                ->select('students.*')
                ->paginate();
            }
        }
        Log::debug('$schoolEps =' . $schoolEps .' students:' . $studentList->count());
        $data = [
            'students'  => $studentList,
            'school_eps'    =>$schoolEps,
            'organiser' =>$organiser,
            'sort_by'    => $sort_by,
            'sort_order' => $sort_order,
            'q'          => $searchQuery ? $searchQuery : '',
        ];
        return view('ManageOrganiser.AllStudents', $data);
    }
    

    /**
     * Edits organiser settings / design etc.
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */
    public function postEditOrganiser(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->find($organiser_id);

        $chargeTax = $request->get('charge_tax');
        if ($chargeTax == 1) {
            $organiser->addExtraValidationRules();
        }

        if (!$organiser->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $organiser->errors(),
            ]);
        }

        $organiser->name = $request->get('name');
        $organiser->about = $request->get('about');
        $organiser->google_analytics_code = $request->get('google_analytics_code');
        $organiser->google_tag_manager_code = $request->get('google_tag_manager_code');
        $organiser->email = $request->get('email');
        $organiser->enable_organiser_page = $request->get('enable_organiser_page');
        $organiser->facebook = $request->get('facebook');
        $organiser->twitter = $request->get('twitter');

        $organiser->tax_name = $request->get('tax_name');
        $organiser->tax_value = $request->get('tax_value');
        $organiser->tax_id = $request->get('tax_id');
        $organiser->charge_tax = ($request->get('charge_tax') == 1) ? 1 : 0;

        if ($request->get('remove_current_image') == '1') {
            $organiser->logo_path = '';
        }

        if ($request->hasFile('organiser_logo')) {
            $organiser->setLogo($request->file('organiser_logo'));
        }

        $organiser->save();

        session()->flash('message', trans("Controllers.successfully_updated_organiser"));

        return response()->json([
            'status'      => 'success',
            'redirectUrl' => '',
        ]);
    }

    /**
     * Edits organiser profile page colors / design
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */
    public function postEditOrganiserPageDesign(Request $request, $organiser_id)
    {
        $organiser = Organiser::scope()->findOrFail($organiser_id);

        $rules = [
            'page_bg_color'        => ['required'],
            'page_header_bg_color' => ['required'],
            'page_text_color'      => ['required'],
        ];
        $messages = [
            'page_header_bg_color.required' => trans("Controllers.error.page_header_bg_color.required"),
            'page_bg_color.required'        => trans("Controllers.error.page_bg_color.required"),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $organiser->page_bg_color        = $request->get('page_bg_color');
        $organiser->page_header_bg_color = $request->get('page_header_bg_color');
        $organiser->page_text_color      = $request->get('page_text_color');

        $organiser->save();

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.organiser_design_successfully_updated"),
        ]);
    }
}
