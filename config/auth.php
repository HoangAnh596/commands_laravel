<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "token"
    |
    */

    'guards' => [
        'api' => ['driver' => 'api'],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Here you may set the options for resetting passwords including the view
    | that is your password reset e-mail. You may also set the name of the
    | table that maintains all of the reset tokens for your application.
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        //
    ],

    'roles' => [
        'HR' => [
            'permissions.campaign.create',
            'permissions.campaign.view',
            'permissions.campaign.update',
            'permissions.campaign.import_emp',
            'permissions.campaign.delete',
            'permissions.campaign.list',
            'permissions.checkpoint.create',
            'permissions.checkpoint.view',
            'permissions.checkpoint.list',
            'permissions.report.view',
        ],
        'DD' => [
            'permissions.campaign.view',
            'permissions.campaign.list',
            'permissions.checkpoint.view',
            'permissions.checkpoint.list',
            'permissions.checkpoint.update',
            'permissions.checkpoint.assign',
            'permissions.checkpoint.approve',
            'permissions.report.view',
        ],
        'PM' => [
            'permissions.campaign.view',
            'permissions.campaign.list',
            'permissions.checkpoint.view',
            'permissions.checkpoint.update',
            'permissions.checkpoint.list',
            'permissions.report.view',
        ],
        'EMP' => [
            'permissions.campaign.view',
            'permissions.campaign.list',
            'permissions.checkpoint.view',
            'permissions.checkpoint.update',
            'permissions.checkpoint.list',
        ],
    ],

    'permissions' => [
        'campaign' => [
            'create'    => 'create campaign',
            'view'      => 'view campaign',
            'update'    => 'update campaign',
            'delete'    => 'delete campaign',
            'list'      => 'list campaign',
            'import_emp'    => 'import employees',
        ],
        'checkpoint' => [
            'create'    => 'create checkpoint',
            'view'      => 'view checkpoint',
            'update'    => 'update checkpoint',
            'delete'    => 'delete checkpoint',
            'list'      => 'list checkpoint',
            'assign'    => 'assign checkpoint',
            'approve'   => 'approve checkpoint',
        ],
        'report' => [
            'view' => 'view report',
        ],
    ],
];
