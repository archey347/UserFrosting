<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Sprinkle\Core\Facades\Debug;

/**
 * Controller class for /auth/* URLs.  Handles account-related authentication.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class AuthenticationController extends SimpleController
{
    public function test(Request $request, Response $response, $args)
    {
    }

    /**
     * Processes an account login request.
     *
     * Processes the request from the form on the login page, checking that:
     * 1. The user is not already logged in.
     * 2. The rate limit for this type of request is being observed.
     * 3. Email login is enabled, if an email address was used.
     * 4. The user account exists.
     * 5. The user account is enabled and verified.
     * 6. The user entered a valid username/email and password.
     * This route, by definition, is "public access".
     *
     * AuthGuard: false
     * Route: /account/login
     * Route Name: {none}
     * Request type: POST
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     */
    public function login(Request $request, Response $response, $args)
    {
        /** @var \UserFrosting\Sprinkle\Core\Alert\AlertStream $ms */
        $ms = $this->ci->alerts;

        /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface $currentUser */
        $currentUser = $this->ci->currentUser;

        /** @var \UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;

        // Return 200 success if user is already logged in
        if ($authenticator->check()) {
            $ms->addMessageTranslated('warning', 'LOGIN.ALREADY_COMPLETE');

            return $response->withJson([], 200);
        }

        /** @var \UserFrosting\Support\Repository\Repository $config */
        $config = $this->ci->config;

        $identityProviders = $this->ci->identityProviders;

        Debug::debug(print_r($identityProviders, true));

        // Get POST parameters
        $params = $request->getParsedBody();

        /*
                // Load the request schema
                $schema = new RequestSchema('schema://requests/login.yaml');

                // Whitelist and set parameter defaults
                $transformer = new RequestDataTransformer($schema);
                $data = $transformer->transform($params);

                // Validate, and halt on validation errors.  Failed validation attempts do not count towards throttling limit.
                $validator = new ServerSideValidator($schema, $this->ci->translator);
                if (!$validator->validate($data)) {
                    $ms->addValidationErrors($validator);

                    return $response->withJson([], 400);
                }

                // Determine whether we are trying to log in with an email address or a username
                $isEmail = filter_var($data['user_name'], FILTER_VALIDATE_EMAIL);

                // Throttle requests
        */
        /** @var \UserFrosting\Sprinkle\Core\Throttle\Throttler $throttler */
        $throttler = $this->ci->throttler;

        $userIdentifier = $data['user_name'];

        $throttleData = [
            'user_identifier' => $userIdentifier,
        ];

        $delay = $throttler->getDelay('sign_in_attempt', $throttleData);
        if ($delay > 0) {
            $ms->addMessageTranslated('danger', 'RATE_LIMIT_EXCEEDED', [
                'delay' => $delay,
            ]);

            return $response->withJson([], 429);
        }

        // Log throttleable event
        $throttler->logEvent('sign_in_attempt', $throttleData);

        // If credential is an email address, but email login is not enabled, raise an error.
        // Note that we do this after logging throttle event, so this error counts towards throttling limit.
        if ($isEmail && !$config['site.login.enable_email']) {
            $ms->addMessageTranslated('danger', 'USER_OR_PASS_INVALID');

            return $response->withJson([], 403);
        }

        // Try to authenticate the user.  Authenticator will throw an exception on failure.
        /** @var \UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;

        $currentUser = $authenticator->attempt(($isEmail ? 'email' : 'user_name'), $userIdentifier, $data['password'], $data['rememberme']);

        $ms->addMessageTranslated('success', 'WELCOME', $currentUser->export());

        // Set redirect, if relevant
        $redirectOnLogin = $this->ci->get('redirect.onLogin');

        return $redirectOnLogin($request, $response, $args);
    }

    /**
     * Render the account sign-in page for UserFrosting.
     *
     * This allows existing users to sign in.
     * By definition, this is a "public page" (does not require authentication).
     *
     * AuthGuard: false
     * checkEnvironment
     * Route: /account/sign-in
     * Route Name: login
     * Request type: GET
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     */
    public function pageSignIn(Request $request, Response $response, $args)
    {
        /** @var \UserFrosting\Support\Repository\Repository $config */
        $config = $this->ci->config;

        /** @var \UserFrosting\Sprinkle\Account\Authenticate\Authenticator $authenticator */
        $authenticator = $this->ci->authenticator;

        // Redirect if user is already logged in
        if ($authenticator->check()) {
            $redirect = $this->ci->get('redirect.onAlreadyLoggedIn');

            return $redirect($request, $response, $args);
        }

        // Load validation rules
        $schema = new RequestSchema('schema://requests/login.yaml');
        $validatorLogin = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'pages/primary-sign-in.html.twig', [
            'page' => [
                'validators' => [
                    'login'    => $validatorLogin->rules('json', false),
                ],
            ],
        ]);
    }
}
