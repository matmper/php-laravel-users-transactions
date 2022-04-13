<?php

return [

    'mail' => [
        'name' => env('MAIL_FROM_NAME'),
        'mail' => env('MAIL_FROM_ADDRESS'),
        'sendblue_key' => env('MAIL_SENDBLUE_KEY')
    ],

    'sms' => [
        'smsdev_key' => env('SMS_DEV_KEY'),
    ],

];