<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;

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
}
