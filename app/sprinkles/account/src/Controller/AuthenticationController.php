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
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;

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

        // Load all of the required twig paths for external
        
        /** @var \UserFrosting\Sprinkle\Account\IdentityProviders\IdentityProviderManager $identityProviderManager */
        $identityProviderManager = $this->ci->identityProviders;

        $identityProviders = $identityProviderManager->getExternalIdentityProviderSlugList();

        $paths = [];

        foreach($identityProviders as $slug) 
        {
            /** @var \UserFrosting\Sprinkle\Account\Database\Models\Interfaces\ExternalIdpInterface */
            $identityProvider = $identityProviderManager->getExternalIdentityProvider($slug);

            $paths[] = $identityProvider->getLoginBtnTemplatePath();
        }

        // Load validation rules
        $schema = new RequestSchema('schema://requests/login.yaml');
        $validatorLogin = new JqueryValidationAdapter($schema, $this->ci->translator);

        return $this->ci->view->render($response, 'pages/sign-in.html.twig', [
            'page' => [
                'validators' => [
                    'login'    => $validatorLogin->rules('json', false),
                ],
                'external_btns' => $paths
            ],
        ]);
    }
}
