<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Authenticate\IdentityProviders\Interfaces;

use UserFrosting\Sprinkle\Account\Authenticate\RawUser;

/**
 * Interface for Primary Identity Providers
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
interface PrimaryIdpInterface
{
    /**
     * Attempt to authenticate with a given user identifier (e.g. username or email address) and a password
     * 
     * @param string $userIdentifier
     * @param string $password
     * @return UserFrosting\Sprinkle\Account\Authenticate\RawUser
     */
    public function attempt($userIdentifier, $password);
}