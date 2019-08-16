<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

/*
 * Account configuration file for UserFrosting.
 */
return [

  'identity_providers' => [
     'primary' => [
         'database' => [
             'class_name' => 'DatabaseAuthenticator',
         ],
         'ldap' => [
             'class_name'       => 'UserFrosting\\Sprinkle\\ExampleSprinkle\\Authenticator\\Primary\\LDAPAuthenticator',
             'pretty_name'      => 'GenericCorp Domain Login',
             'description'      => 'Login using a Generic Corperation Domain account',
             'allow_new_users'  => true,
             'allow_association'=> true,
             'options'          => [
                 'account_suffix'     => '@example.com',
                 'domain_controllers' => [
                     'dc1.example.com',
                     'dc2.example.com',
                 ],
                 'admin_username' => '',
                 'admin_password' => '',
             ],
         ],
     ],
     'external' => [
         'github' => [
             'class_name' => 'UserFrosting\\Sprinkle\\ExampleSprinkle\\Authenticator\\External\\GithubAuthenticator',
             'pretty_name'=> 'GitHub',
             'description'=> 'Login using GitHub',
             'options'    => [
                 'app_id'     => 'A App ID',
                 'app_secret' => 'Some secret.....',
             ],
         ],
     ],
     'secondary' => [
         'google_time_code' => [
             'class_name' => 'UserFrosting\\Sprinkle\\ExampleSprinkle\\Authenticator\\Secondary\\GoogleTimeCodeAuthenticator',
             'pretty_name'=> 'Google Authenticator',
             'description'=> 'Time based code',
             'options'    => [
                 'salt' => 'AKHD3425...',
             ],
         ],
     ],
 ],
     'secondary_authentication' => [
         'enabled'                 => true,
         '__comment'               => 'Enables/disables 2FA... would be worth creating some slim middleware to block all 2FA routes when disabled',
         'setup_on_login'          => true,
         '__comment'               => 'Whether to show the 2FA setup form on login',
         'force'                   => true,
         '__comment'               => 'Whether a user must have 2FA to login (will do the same as the above option, but will not have a skip button',
],

    /*
    * ----------------------------------------------------------------------
    * User Cache Config
    * ----------------------------------------------------------------------
    * Cache current user info for a given time to speed up process.
    * Set to zero to disable.
    */
    'cache' => [
        'user' => [
            'delay' => 120, // In minutes
            'key'   => '_user',
        ],
    ],

    /*
    * ----------------------------------------------------------------------
    * AuthorizationManager Debug
    * ----------------------------------------------------------------------
    * Turn this on to send AuthorizationManager::checkAccess process details
    * to log. This can help debugging your permissions and roles
    */
    'debug' => [
        'auth' => false,
    ],

    /*
    * ----------------------------------------------------------------------
    * Configuration for the 'password reset' feature
    * ----------------------------------------------------------------------
    */
   'password_reset' => [
        'algorithm'  => 'sha512',
        'timeouts'   => [
            'create' => 86400,
            'reset'  => 10800,
        ],
    ],

    /*
    * ----------------------------------------------------------------------
    * RememberMe Package Settings
    * ----------------------------------------------------------------------
    * See https://github.com/gbirke/rememberme for an explanation of these settings
    *
    * Note that the 'domain' field can be set to match your top-level-domain if you
    * want to send the rememberme to all hosts in your domain.  An automatic config
    * of this can be done in your config.php with code similar to this:
    *
    * if (!empty($_SERVER['SERVER_NAME']) && filter_var($_SERVER['SERVER_NAME'], \FILTER_VALIDATE_IP) === false) {
    *    $darr = explode(".", $_SERVER['SERVER_NAME']);
    *    array_shift($darr);
    *    $conf['session']['cookie_parameters'] = [ "lifetime" => 86400, "domain" => ".".join(".", $darr), "path" => "/" ];
    *    $conf['remember_me'] = [ "domain" => ".".join(".", $darr) ];
    * }
    *
    * (Or, for production, you can hard-code the domain rather than calculating it on each page load)
    *
    * This is DELIBERATELY NOT TURNED ON BY DEFAULT!
    *
    * If you enable the 'domain' (on both the session and the remember_me cookies)
    * you will be sending your authentication cookies to every machine in the
    * domain you are using. This may not be bad if you control the domain, but
    * if you are using a VPS and the hostname of the machine you are connecting to
    * is, for example, host2.vps.blah.com, and you connect to host20.vps.blah.com,
    * your browser will send your (super secret) cookies to host20.vps.blah.com.
    *
    * You only want to turn this on if you want machine1.foo.com to receive the
    * cookies that THIS MACHINE (machine2.foo.com) set.
    */
    'remember_me' => [
        'cookie' => [
            'name' => 'rememberme',
        ],
        'domain'      => null,
        'expire_time' => 604800,
        'session'     => [
            'path' => '/',
        ],
    ],

    /*
    * ----------------------------------------------------------------------
    * Reserved user IDs
    * ----------------------------------------------------------------------
    * Master (root) user will be the one with this user id. Same goes for
    * guest users
    */
    'reserved_user_ids' => [
        'guest'  => -1,
        'master' => 1,
    ],

    /*
    * ----------------------------------------------------------------------
    * Account Session config
    * ----------------------------------------------------------------------
    * The keys used in the session to store info about authenticated users
    */
    'session' => [
        'keys' => [
            'current_user_id'  => 'account.current_user_id',    // the key to use for storing the authenticated user's id
            'captcha'          => 'account.captcha',             // Key used to store a captcha hash during captcha verification
        ],
    ],

    /*
    * ----------------------------------------------------------------------
    * Account Site Settings
    * ----------------------------------------------------------------------
    * "Site" settings that are automatically passed to Twig. Use theses
    * settings to control the login, password (re)set and registration
    * processes
    */
    'site' => [
        'login' => [
            'enable_email' => true, // Set to false to allow login by username only
        ],
        'registration' => [
            'enabled'                    => true, //if this set to false, you probably want to also set require_email_verification to false as well to disable the link on the signup page
            'captcha'                    => true,
            'require_email_verification' => true,
            // Default roles and other settings for newly registered users
            'user_defaults' => [
                'locale' => 'en_US',
                'group'  => 'terran',
                'roles'  => [
                    'user' => true,
                ],
            ],
        ],
        'password' => [
            'length' => [
                'min' => 8,
                'max' => 25,
            ],
        ],
    ],

    /*
    * ----------------------------------------------------------------------
    * Throttles Configuration
    * ----------------------------------------------------------------------
    * No throttling is enforced by default. Everything is setup in
    * production mode. See http://security.stackexchange.com/a/59550/74909
    * for the inspiration for our throttling system
    */
    'throttles' => [
        'check_username_request' => null,
        'password_reset_request' => null,
        'registration_attempt'   => null,
        'sign_in_attempt'        => null,
        'verification_request'   => null,
    ],

    /*
    * ----------------------------------------------------------------------
    * Configuration for the 'email verification' feature
    * ----------------------------------------------------------------------
    */
    'verification' => [
        'algorithm' => 'sha512',
        'timeout'   => 10800,
    ],
];
