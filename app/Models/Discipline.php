<?php

namespace App\Models;

use App\Attendize\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;


class Discipline extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'discipline_name' => ['required'],
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
        'discipline_name',
    ];
	
	   /**
     * Get the levels for the blog post.
     */
    public function competitions()
    {
		return $this->hasMany(\App\Models\Competition::class);
    }

}
