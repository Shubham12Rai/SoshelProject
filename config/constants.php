<?php

return [


    'CODE' => [
        'continue' => 100,
        'success' => 200,
        'created' => 201,
        'accepted' => 202,
        'badRequest' => 400,
        'unauthorized' => 401,
        'paymentRequired' => 402,
        'notFound' => 404,
        'unprocessableEntity' => 422,
        'internalServerError' => 500,
        'badGateway' => 502,
        'permissionDenied' => 403,
        'validation' => 410
    ],

    'LOGIN_TYPE' => [
        'google' => 1,
        'instagram' => 2,
        'apple' => 3
    ],

    'IMAGE_PATH' => [
        'profile_photo' => 'uploads/profile_photo/'
    ],

    'LOCATION' => [
        'earthRadius' => 6371,  // in km
        'radius' => 16.0934,    // in km
        'nearByRadius' => 0.1,
        'polarMapNearByRadius' => 100,
        'maxRadius' => 0.1,    // Represents 100 meters
        'polarMapDefaultRadius' => 10     // in km

    ],
    'TIME_PERIOD' => [
        'today' => 'Today',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'currentMonth' => 'Currentmonth',
        'lastThreeMonths' => 'Lastthreemonths',
        'lastSixMonths' => 'Lastsixmonths',
        'oneYear' => 'Oneyear',
    ],

    'REFER' => [
        'REFER_CODE' => 'R00001',
    ],

    'TRANSACTION_MEDIUM' => [
        'applePay' => 'Apple Pay',
        'debitCard' => 'Debit Card',
        'creditCard' => 'Credit Card',
    ],

    'PUSH_NOTIFICATION' => [
        'server_key' => env('SERVER_API_KEY'),
        'fcm_url' => env('FCM_URL')
    ],

    'PLAN_TYPE' => [
        'Standard' => 1,
        'Premium' => 2,
        'In-app purchase' => 3,
        'All' => 4
    ],
    'NOTIFICATION_TYPE' => [
        'all' => 'All',
        'selectedUser' => 'Selected User'
    ],
    'FEEDBACK_TYPE' => [
        'like' => 1,
        'dislike' => 2
    ],
    'EMAIL_VERIFY' => [
        'expiryTime' => 2 // in minutes
    ],

    'STATIC_CONTENT' => [
        'Term&Conditions' => 'terms-and-conditions',
        'TermConditionsWhereClause' => 'Term & Conditions',
        'privacyPolicy' => 'privacy-policy',
        'privacyPolicyWhereClause' => 'Privacy Policy',
        'faqs' => 'faq'
    ],

    'EXPIRE_TIME' => [
        'matchExpireTime' => 2880  // in minutes
    ],

    'ENCRYPT_DECRYPT_KEY' => [
        'cipher' => 'aes-128-cbc',
        'key' => env('key'),
        'iv' => env('iv')
    ]

];