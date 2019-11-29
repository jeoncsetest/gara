<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Str;
use Image;

class Eps extends MyBaseModel implements AuthenticatableContract
{
    use Authenticatable;
    /**
     * The validation rules for the model.
     *
     * @var array $rules
     */
    protected $rules = [
        'eps_code'           => ['required'],
    ];



    /**
     * The validation error messages for the model.
     *
     * @var array $messages
     */
    protected $messages = [
        'eps_code.required'        => 'codice eps è obbligatorio',
		];
}

