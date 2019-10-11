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
class School extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'user_id' => ['required'],
        'eps'  => ['required'],
        'name'	=> ['required'],
		'phone'	=> ['required'],
		'email'	=> ['required'],
		'password'	=> ['required'],
		'city'	=> ['required'],
		'place'	=> ['required'],
		'address'	=> ['required']
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
		'user_id',
        'eps',
        'name',
		'phone',
		'email',
		'password',
		'city',
		'place',
		'address'
    ];
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
  }
      /**
     * A school may be associated with an user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function students()
    {
        return $this->hasMany(\App\Models\Student::class, 'school_eps', 'eps');
    }
}
