<?php
namespace App\Models;

use App\Attendize\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

    /*
      Attendize.com   - Event Management & Ticketing
     */

/**
 * Description of Subscriptions.
 *
 * @author Dave
 */
class Student extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'school_eps' => ['required'],
        'name'  => ['required'],
        'surname'	=> ['required'],
		    'fiscal_code'	=> ['required'],
		    'birth_date'	=> ['required'],
		    'birth_place'	=> ['required'],
        'user_id'	=> ['required'],
        'email'	=> ['required'],
        'phone'	=> ['required'],
    ];
	
		 /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'school_eps',
        'email',
        'phone',
        'name',
        'surname',
		    'fiscal_code',
	    	'birth_date',
		    'birth_place',
		    'user_id'
    ];
	
	public function competition()
	{
		return $this->belongsTo(Competition::class, 'competition_id', 'id');
	}
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
	public function event()
	{
		return $this->belongsTo(Event::class, 'event_id', 'id');
	}	
	public function school()
	{
		return $this->belongsTo(School::class, 'school_eps', 'eps');
	}
}
