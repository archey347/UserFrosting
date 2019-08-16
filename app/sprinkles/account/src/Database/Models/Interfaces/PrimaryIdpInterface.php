<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Models\Interfaces;

/**
 * Primary Identity Provider interface.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
interface PrimaryIdpInterface
{
    public function attempt($userIdentifier, $password);

    public function logout($user);

    public function getUser();

    public function createUser($rawUser);

    public function doUsersMatch($dbUser, $rawUser);
}
