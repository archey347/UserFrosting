<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\PrimaryIdpInterface;
use UserFrosting\Sprinkle\Account\Authenticate\RawUser;
use Interop\Container\ContainerInterface;

/**
 * Primary Database Identity Provider
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class DatabaseIdp implements PrimaryIdpInterface
{
    /** 
     * @var Config 
     */
    protected $config;

    public function __construct($config) 
    {
        $this->config = $config;
    }

    /**
     * attempt - Attempt to authenticate a user using a standard username/password combination (returns a RawUser object if successful)
     * 
     * @param mixed $userIdentifier A unique id for the user logging in (e.g. username/email address)
     * @param mixed $password       The password for the user
     * 
     * @return RawUser
     */
    public function attempt($userIdentifier, $password)
    {

    }

    /**
     * logout - logout the user with the external site (if required)
     * 
     * 
     */
    public function logout($user) 
    {

    }

    /**
     * updateUser - Returns an edited user with details from an external user
     * 
     * This function is irrelevant as for the user to login they must have had a user record in the database
     *
     * @param  UserInterface $user    The user to ammend the data to
     * @param  RawUser       $rawUser The raw user to get the data from
     *
     * @return UserInterface
     */
    public function updateUser(UserInterface $user, RawUser $rawUser)
    {
        return $user;
    }

    /**
     * Not sure about this function yet
     */
    public function doUsersMatch($dbUser, $rawUser);
}