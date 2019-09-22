<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Authenticate;

/**
 * Raw user class
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class RawUser
{
    /**
     * @var mixed A unique id of the user from the external site
     */
    public $id;

    /**
     * @var string The type of identity provider this user came from (either primary or external)
     */
    public $identityProviderType;

    /**
     * @var int The id of the identity provider in the database the user came from
     */
    public $identityProviderId;

    /**
     * @var mixed Any data about the user which can be used to create a "full" UF user.
     */
    public $metaData;
}
