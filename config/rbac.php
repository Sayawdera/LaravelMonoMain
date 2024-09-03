<?php




return [


    /*
    |--------------------------------------------------------------------------
    |  SUPER_ADMIN
    |--------------------------------------------------------------------------
    |
    | permission roles for SUPER_ADMIN
    | just add array controllers methods
    |
    |
    */
    'super_admin' => [
        'users' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'roles',
            'checkUserToken',
            'updateYourself',
            'resetPassword',
            'logout',
        ],
        'countries' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'check-email-verification' => [
            'checkEmailVerification',
        ],
    ],





    /*
    |--------------------------------------------------------------------------
    | MODERATOR
    |--------------------------------------------------------------------------
    |
    | permission roles for MODERATOR
    | just add array controllers methods
    |
    |
    */
    'moderator' => [
        'users' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'roles',
            'checkUserToken',
            'updateYourself',
            'resetPassword',
            'logout',
        ],
        'countries' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'check-email-verification' => [
            'checkEmailVerification',
        ],
    ],






    /*
    |--------------------------------------------------------------------------
    | EDITOR
    |--------------------------------------------------------------------------
    |
    | permission roles for EDITOR
    | just add array controllers methods
    |
    |
    */
    'editor' => [
        'users' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'roles',
            'checkUserToken',
            'updateYourself',
            'resetPassword',
            'logout',
        ],
        'countries' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'check-email-verification' => [
            'checkEmailVerification',
        ],
    ],





    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    |
    | permission roles for SUPER USER
    | just add array controllers methods
    |
    |
    */
    'user' => [
        'users' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'roles',
            'checkUserToken',
            'updateYourself',
            'resetPassword',
            'logout',
        ],
        'countries' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'check-email-verification' => [
            'checkEmailVerification',
        ],
    ],





    /*
    |--------------------------------------------------------------------------
    | SUPER_USER
    |--------------------------------------------------------------------------
    |
    | permission roles for SUPER_USER
    | just add array controllers methods
    |
    |
    */
    'super_user' => [
        'users' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'roles',
            'checkUserToken',
            'updateYourself',
            'resetPassword',
            'logout',
        ],
        'countries' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'check-email-verification' => [
            'checkEmailVerification',
        ],
    ],




    /*
    |--------------------------------------------------------------------------
    | NEW_USER
    |--------------------------------------------------------------------------
    |
    | permission roles for NEW_USER
    | just add array controllers methods
    |
    |
    */
    'new_user' => [
        'users' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
            'roles',
            'checkUserToken',
            'updateYourself',
            'resetPassword',
            'logout',
        ],
        'countries' => [
            'index',
            'store',
            'show',
            'update',
            'destroy',
        ],
        'check-email-verification' => [
            'checkEmailVerification',
        ],
    ],
];
