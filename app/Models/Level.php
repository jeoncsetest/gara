<?php

namespace App\Models;

use App\Attendize\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;


class Level extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'event_id' => ['required'],
        'competition_id'  => ['required'],
        'level'	=> ['required'],
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
        'event_id',
        'competition_id',
        'level'
    ];
	public function competition()
{
	return $this->belongsTo(Competition::class);
}
}
