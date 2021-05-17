<?php
// config for Macmotp/HasCodegen
return [
    /*
    |--------------------------------------------------------------------------
    | The attribute of the model to build the code from.
    | For example, if your model has a column 'name' you can build the code from this attribute.
    | If empty, will generate random codes.
    |--------------------------------------------------------------------------
    */
    'build-from' => '',

    /*
    |--------------------------------------------------------------------------
    | The column use to save the code into the model.
    |--------------------------------------------------------------------------
    */
    'code-column' => 'code',

    /*
    |--------------------------------------------------------------------------
    | The length of the code to generate.
    |--------------------------------------------------------------------------
    */
    'code-length' => 6,

    /*
    |--------------------------------------------------------------------------
    | Sanitize level.
    | 1. Low/Default: will filter out anything is not a letter or a digit;
    | 2. Medium: will filter out (O - 0 - Q - I - 1) characters;
    | 3. High: will filter out (2 - Z - 4 - A - 5 - S - 8 - B - U - V - Y) characters;
    | Levels are inclusive, e.g. the highest level will apply also regex of level low and medium.
    |--------------------------------------------------------------------------
    */
    'sanitize-level' => 1,

    /*
    |--------------------------------------------------------------------------
    | Prepend a string.
    |--------------------------------------------------------------------------
    */
    'prepend' => '',

    /*
    |--------------------------------------------------------------------------
    | Append a string.
    |--------------------------------------------------------------------------
    */
    'append' => '',

    /*
    |--------------------------------------------------------------------------
    | Maximum accepted number of attempts for the generation.
    |--------------------------------------------------------------------------
    */
    'max-attempts' => 10000,
];
