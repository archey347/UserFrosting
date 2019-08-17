<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Models\Interfaces;

use UserFrosting\Sprinkle\Account\Authenticate\RawUser;
/**
 * Primary Identity Provider interface.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
interface PrimaryIdpInterface
{
    /**
     * attempt - Attempt to authenticate a user using a standard username/password combination (returns a RawUser object if successful)
     * 
     * @param mixed $userIdentifier A unique id for the user logging in (e.g. username/email address)
     * @param mixed $password       The password for the user
     * 
     * @return RawUser
     */
    public function attempt($userIdentifier, $password);

    /**
     * logout - logout the user with the external site (if required)
     * 
     * 
     */
    public function logout($user);

    /**
     * updateUser - Returns an edited user with details from an external user
     * 
     * This will also include associating the user (if not already) with this identity provider so that the same user can login again
     *
     * @param  UserInterface $user    The user to ammend the data to
     * @param  RawUser       $rawUser The raw user to get the data from
     *
     * @return UserInterface
     */
    public function updateUser(UserInterface $user, RawUser $rawUser);

    /**
     * Not sure about this function yet
     */
    public function doUsersMatch($dbUser, $rawUser);
}
