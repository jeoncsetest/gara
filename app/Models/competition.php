<?php

namespace App\Models;

use App\Attendize\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;


class Competition extends MyBaseModel
{
      /**
     * The validation rules
     *
     * @var array $rules
     */
    protected $rules = [
        'event_id' => ['required'],
        'title'  => ['required'],
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
        'title',
        'level',
        'category'
    ];
	
	   /**
     * Get the levels for the blog post.
     */
    public function levels()
    {
		return $this->hasMany(\App\Models\Level::class);
    }
		   /**
     * Get the  for the blog post.
     */
    public function categories()
    {
		return $this->hasMany(\App\Models\Category::class);
    }
}
