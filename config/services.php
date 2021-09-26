<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
     */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'ms_shopping_cart' => [
        'url' => env('MS_SHOPPING_CART_HOST'),
    ],
    'place_to_pay' => [
        'host' => env('PLACE_TO_PAY_HOST'),
        'login' => env('PLACE_TO_PAY_LOGIN'),
        'secret_key' => env('PLACE_TO_PAY_SECRET_KEY'),
        'return_url' => env('URL_FRONTEND'),
    ],
    'strava' => [
        'host' => env('STRAVA_HOST'),
        'radar_app_id' => env('RADAR_APP_ID'),
    ],
    'sendgrid' => [
        'user_template_id' => env('SENDGRID_TEMPLATE_ID'),
        'api_key' => env('SENDGRID_API_KEY'),
        'from_email' => env('SENGRID_EMAIL'),
    ],
    'registerCivil' => [
        'urlAuth' => env('URL_AUTH'),
        'urlFindUser' => env('URL_FIND_USER'),
        'applicationCode' => env('APPLICATION_CODE'),
        'platformCode' => env('PLATFORM_CODE'),
        'operativeSystem' => env('OPERATIVE_SYSTEM'),
        'browserDevice' => env('BROWSER_DEVICE'),
        'ipAdress' => env('IP_ADRESS'),
        'username' => env('USERNAME_REGISTER_CIVIL'),
        'password' => env('PASSWORD'),
        'grandType' => env('GRANT_TYPE'),
        'clientId' => env('CLIENT_ID'),
    ],
    'kushki' => [
        'url' => env('KUSHKI_URL'),
        'privateMerchantId' => env('PRIVATE_MERCHANT_ID'),
        'webhookSignature' => env('WEBHOOK_SIGNATURE'),
        'succesfullCharge' => env('SUCCESSFULL_CHARGE'),
        'failedRetry' => env('FAILED_RETRY'),
        'lastRetry' => env('LAST_RETRY'),
        'declinedCharge' => env('DECLINED_CHARGE'),
        'subscriptionDelete' => env('SUBSCRIPTION_DETETE')
    ],
    'salesService' => [
        'url' => env('URL_SALE_SERVICE'),
        'applicationCode' => env('APPLICATION_CODE'),
        'platformCode' => env('PLATFORM_CODE'),
        'operativeSystem' => env('OPERATIVE_SYSTEM'),
        'browserDevice' => env('BROWSER_DEVICE'),
        'username' => env('USERNAME_SALE_SERVICE'),
        'password' => env('PASSWORD_SALE_SERVICE'),
        'grandType' => env('GRANT_TYPE'),
        'clientId' => env('CLIENT_ID'),
        'succursaleName' => env('SUCCURSALE_NAME'),
        'placePayment' => env('PLACE_PAYMENT'),
        'userCreation' => env('USER_CREATION'),
        'programCreation' => env('PROGRAM_CREATION'),
    ],
    'sigmedService' => [
        'urlWsdl' => env('URL_SIGMED')
    ],
    'atMailing' => [
        'url' => env('URL_SEND_EMAIL'),
        'applicationCode' => env('APPLICATION_CODE'),
        'platformCode' => env('PLATFORM_CODE'),
        'operativeSystem' => env('OPERATIVE_SYSTEM'),
        'browserDevice' => env('BROWSER_DEVICE'),
        'username' => env('USERNAME_SALE_SERVICE'),
        'password' => env('PASSWORD_SALE_SERVICE'),
        'grandType' => env('GRANT_TYPE'),
        'clientId' => env('CLIENT_ID'),
        'succursaleName' => env('SUCCURSALE_NAME'),
        'placePayment' => env('PLACE_PAYMENT'),
        'userCreation' => env('USER_CREATION'),
        'programCreation' => env('PROGRAM_CREATION'),
    ],
];
