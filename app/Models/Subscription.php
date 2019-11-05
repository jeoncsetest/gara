<?php
namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
    /*
      Attendize.com   - Event Management & Ticketing
     */
/**
 * Description of Subscriptions.
 *
 * @author Dave
 */
class Subscription extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'event_id' => ['required'],
        'competition_id'  => ['required'],
        'user_id'	=> ['required'],
		'quantity_reserved'	=> ['required'],
		'expires'	=> ['required'],
		'session_id'	=> ['required'],
		
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
        'order_id',
        'event_id',
        'competition_id',
        'user_id',
        'account_id',
		    'quantity_reserved',
		    'expires',
		    'session_id'
    ];
    
     /**
     * Generate a private reference number for the attendee. Use for checking in the attendee.
     *
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            do {
                //generate a random string using Laravel's str_random helper
                $token = Str::Random(15);
            } //check if the token already exists and if it does, try again
            while (Attendee::where('private_reference_number', $token)->first() || Subscription::where('private_reference_number', $token)->first());
            $order->private_reference_number = $token;
        });
    }
	
	public function competition()
	{
		return $this->belongsTo(Competition::class, 'competition_id', 'id');
	}
  
  public function order()
	{
		return $this->belongsTo(Order::class, 'order_id', 'id');
	}
  
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
	public function event()
	{
		return $this->belongsTo(Event::class, 'event_id', 'id');
	}
  public function participants()
  {
  return $this->hasMany(\App\Models\Participant::class);
  }
      /**
     * Get the attendee reference
     *
     * @return string
     */
    public function getReferenceAttribute()
    {
        return $this->order->order_reference . '-' . $this->reference_index;
    }
    /**
     * The attributes that should be mutated to dates.
     *
     * @return array $dates
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'arrival_time'];
    }
}