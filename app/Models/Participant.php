<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;


    /*
      Attendize.com   - Event Management & Ticketing
     */

/**
 * Description of Subscriptions.
 *
 * @author Dave
 */
class participant extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'student_id' => ['required'],
        'subscription_id'  => ['required']	
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
        'subscription_id',
        'student_id'
    ];
	
	public function Student()
	{
		return $this->belongsTo(Student::class, 'student_id', 'id');
	}
	
		public function subscription()
	{
		return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
	}
}
