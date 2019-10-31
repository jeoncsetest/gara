<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | La following language lines contain La default error messages used by
    | La validator class. Some of Lase rules have multiple versions such
    | as La size rules. Feel free to tweak each of Lase messages here.
    |
    */

    'accepted'             => 'La :attribute deve essere accettato',
    'active_url'           => 'L\' :attribute non è un URL valido.',
    'after'                => 'La :attribute deve essere una data successiva :date.',
    'alpha'                => 'La :attribute può contenere solo lettere.',
    'alpha_dash'           => 'La :attribute può contenere solo lettere, numeri, e segni.',
    'alpha_num'            => 'La :attribute può contenere solo lettere e numeri.',
    'array'                => 'La :attribute deve essere un array.',
    'before'               => 'La :attribute deve essere una date precedente a :date.',
    'between'              => [
        'numeric' => 'La :attribute deve essere tra :min e :max.',
        'file'    => 'La :attribute deve essere tra :min e :max kilobytes.',
        'string'  => 'La :attribute deve essere tra :min e :max caratteri.',
        'array'   => 'La :attribute deve avere tra :min e :max elementi.',
    ],
    'boolean'              => 'La :attribute campo deve essere vero o falso.',
    'confirmed'            => 'La :attribute la conferma non corrisponde.',
    'date'                 => 'La :attribute non è una data valida.',
    'date_format'          => 'La :attribute non corrisponde al formato :format.',
    'different'            => 'La :attribute e :oLar deve essere differente.',
    'digits'               => 'La :attribute deve essere :digits segni.',
    'digits_between'       => 'La :attribute deve essere tra :min e :max segni.',
    'email'                => 'La :attribute deve essere un indirizzo email valido.',
    'filled'               => 'La :attribute campo è obbligatorio.',
    'exists'               => 'La :attribute selezionato è invalido.',
    'image'                => 'La :attribute deve essere un immagine.',
    'in'                   => 'La :attribute selezionato è invalido.',
    'integer'              => 'La :attribute deve essere un integer.',
    'ip'                   => 'La :attribute deve essere un indirizzo IP valido.',
    'max'                  => [
        'numeric' => 'La :attribute potrebbe non essere maggiore di :max.',
        'file'    => 'La :attribute non deve essere maggiore di :max kilobytes.',
        'string'  => 'La :attribute non deve essere maggiore di :max caratteri.',
        'array'   => 'La :attribute non deve avere più di :max elementi.',
    ],
    'mimes'                => 'La :attribute deve essere a file di tipo: :values.',
    'min'                  => [
        'numeric' => 'La :attribute deve essere almeno :min.',
        'file'    => 'La :attribute deve essere almeno :min kilobytes.',
        'string'  => 'La :attribute deve essere almeno :min caratteri.',
        'array'   => 'La :attribute deve essere di almeno :min elementi.',
    ],
    'not_in'               => 'La selezione :attribute è invalido.',
    'numeric'              => 'La :attribute deve essere a numero.',
    'regex'                => 'La :attribute è un formato invalido.',
    'required'             => 'La :attribute campo è obbligatorio.',
    'required_if'          => 'La :attribute campo è obbligatorio quando :oLar is :value.',
    'required_with'        => 'La :attribute campo è obbligatorio quando :values is present.',
    'required_with_all'    => 'La :attribute campo è obbligatorio quando :values is present.',
    'required_without'     => 'La :attribute campo è obbligatorio quando :values is not present.',
    'required_without_all' => 'La :attribute campo è obbligatorio quando none of :values are present.',
    'same'                 => 'La :attribute e :oLar devono combaciare.',
    'size'                 => [
        'numeric' => 'La :attribute deve essere :size.',
        'file'    => 'La :attribute deve essere :size kilobytes.',
        'string'  => 'La :attribute deve essere :size caratteri.',
        'array'   => 'La :attribute must contain :size elementi.',
    ],
    'unique'               => 'La :attribute è già stato inseito.',
    'url'                  => 'La :attribute è un formato invalio.',
    'timezone'             => 'La :attribute deve essere una zona valida.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using La
    | convention "attribute.rule" to name La lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'terms_agreed' => [
            'required' => 'Accetta i nostri Termini di servizio.'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | La following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
