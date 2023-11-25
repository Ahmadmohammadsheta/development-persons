<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // ama.config allowed_origins
    // 'allowed_origins' => ['*'],
    // 'allowed_origins' => [env('Frontend URL', 'default')],
    'allowed_origins' => [env('CORS_ALLOWED_ORIGINS', 'http://localhost:8000')],
    // 'allowed_origins' => ['http://localhost:8080'],

    'allowed_origins_patterns' => [],
    // use this configure & make allowed_origins null
    // 'allowed_origins_patterns' => ["*localhost*"],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 'supports_credentials' => false,
    // ama.config supports_credentials
    'supports_credentials' => true,

];

// ama.config example
// return [

//     /*
//      * The allowed origins that can make cross-origin requests.
//      *
//      * @var array
//      */
//     'allowed_origins' => [
//         env('http://localhost:8080', 'http://localhost:8000'),
//     ],

//     /*
//      * Allowed methods for CORS requests.
//      *
//      * @var array
//      */
//     'allowed_methods' => [
//         'GET',
//         'POST',
//         'PUT',
//         'PATCH',
//         'DELETE',
//         'OPTIONS',
//     ],

//     /*
//      * Allowed headers for CORS requests.
//      *
//      * @var array
//      */
//     'allowed_headers' => [
//         '*',
//     ],

//     /*
//      * Exposed headers for CORS requests.
//      *
//      * @var array
//      */
//     'exposed_headers' => [
//         'Authorization',
//         'Content-Type',
//     ],

//     /*
//      * Max age for CORS requests.
//      *
//      * @var int
//      */
//     'max_age' => 60 * 60,

//     /*
//      * Whether to allow credentials for CORS requests.
//      *
//      * @var bool
//      */
//     'allow_credentials' => true,

//     /*
//      * Whether to provide preflight responses for CORS requests.
//      *
//      * @var bool
//      */
//     'allow_preflight' => true,
// ];
