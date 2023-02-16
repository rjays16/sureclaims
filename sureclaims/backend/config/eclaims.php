<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base URL used to access the eclaims web service
    |--------------------------------------------------------------------------
    |
    */
    //    'hie_base_url' => 'http://54.255.202.136/hietracer/api/',
    'hie_api_url'  => env('HIE_API_URL'),
    // sample: http://54.255.202.136/hietracer/hie/file/5b17a5f0fe5f72ee5a8b4596.html
    'hie_file_url' => env('HIE_FILE_URL'),

    'hie_client_id' => 'spmc.doh.gov.ph',

    'hie_client_secret' => 's3cr3ts',

    'service_provider' => 'SEGWORKS',

    'hospital_email'     => 'eclaims@segworks.com',
    

    'philhealth_address' => 'Citystate Centre, 709 Shaw Boulevard, Pasig City. Healthline 441-7444 ',


    /*
    |--------------------------------------------------------------------------
    | Formats used in the application
    |--------------------------------------------------------------------------
    */
    'format'             => [
        /**
         * Formats used for the eclaims web service
         */
        'web_service' => [
            // Date/Time format
            'date_time' => 'm-d-Y h:i:sA',

            // Date format
            'date'      => 'm-d-Y',

            // Time format
            'time'      => 'h:i:sA',
        ],
    ],
];