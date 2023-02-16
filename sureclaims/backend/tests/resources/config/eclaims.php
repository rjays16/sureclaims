<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Username used to access the eClaims web service
    |--------------------------------------------------------------------------
    |
    */
    'username' => '',

    /*
    |--------------------------------------------------------------------------
    | Password to be used for the eClaims web service
    |--------------------------------------------------------------------------
    |
    */
    'password' => '',

    /*
    |--------------------------------------------------------------------------
    | Hospital code to be used
    |--------------------------------------------------------------------------
    |
    */
    'hospital_code' => '950102',

    /*
    |--------------------------------------------------------------------------
    | Auto-capitalize text fields
    |--------------------------------------------------------------------------
    |
    */
    'auto_caps' => true,

    /*
    |--------------------------------------------------------------------------
    | Store the unencrypted fileformat for document uploads
    |--------------------------------------------------------------------------
    |
    */
    'keep_raw_uploads' => true,

    /*
    |--------------------------------------------------------------------------
    | For auto claim numbering
    |--------------------------------------------------------------------------
    |
    */
    'auto_claim_number' => [
        'format' => '{prefix}-{number}',
        'prefix' => function () {
            return date( 'y' );
        },
        'number' => function ( $index, $length ) {
            return str_pad( $index, $length, "0", STR_PAD_LEFT );
        },
        'digits' => 10,
        'decode' => function ( $number ) {
            return (int)substr( $number, 3 );
        },
        'encode' => function ( $index ) {
            return $index;
        }
    ],

    /*
    |--------------------------------------------------------------------------
    | For auto claim numbering
    |--------------------------------------------------------------------------
    |
    */
    'auto_transmittal_number' => [
        'format' => '{prefix}{number}',
        'prefix' => function () {
            return date( 'y' );
        },
        'number' => function ( $index, $length ) {
            return str_pad( $index, $length, "0", STR_PAD_LEFT );
        },
        'digits' => 10,
        'decode' => function ( $number ) {
            return (int)substr( $number, 3 );
        },
        'encode' => function ( $index ) {
            return $index;
        }
    ],
];
