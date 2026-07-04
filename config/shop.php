<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shop Information
    |--------------------------------------------------------------------------
    */

    'store' => [

        'name'       => env('APP_NAME', 'ShopMe'),
        'currency'   => 'INR',
        'timezone'   => 'Asia/Kolkata',
        'pagination' => 12,

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Admin
    |--------------------------------------------------------------------------
    */

    'admin' => [

    'name'     => env('ADMIN_NAME', 'Super Admin'),
    'email'    => env('ADMIN_EMAIL', 'admin@shopme.com'),
    'password' => env('ADMIN_PASSWORD', 'admin123'),
    'status'   => env('ADMIN_STATUS', true),

    ],

];