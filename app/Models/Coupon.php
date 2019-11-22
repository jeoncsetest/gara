<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Str;
use Image;

class Coupon extends MyBaseModel implements AuthenticatableContract
{
    use Authenticatable;
    /**
     * The validation rules for the model.
     *
     * @var array $rules
     */
    protected $rules = [
        'code'           => ['required'],
        'type'          => ['required', 'email'],
        'organiser_id' => ['required'],
    ];



    /**
     * The validation error messages for the model.
     *
     * @var array $messages
     */
    protected $messages = [
        'code.required'        => 'codice coupon è obbligatorio',
        'type.required'        => 'tipo coupon è obbligatorio',
        'organiser_id.required' => 'organiser_id è obbligatorio',
    ];

    /**
     * The account associated with the organiser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser()
    {
        return $this->belongsTo(\App\Models\Organiser::class);
    }
}

