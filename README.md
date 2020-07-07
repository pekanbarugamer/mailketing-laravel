# mailketing-laravel
Mailketing Laravel API Integration


#Cara Instalasi via Composer

composer require mailketing/mailketing-laravel

add mailketing token di config/services.php

'mailketing' => [
        'api_key' => env('MAILKETING_API_TOKEN'),
    ],
    
    Silahkan tambahkan di dalam .env file
    
    MAIL_DRIVER=mailketing

MAILKETING_API_TOKEN='API_TOKEN_ANDA'
