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
 * External Identity Provider interface.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
interface ExternalIdpInterface
{
    public function redirect();

    public function verify($data);

    public function getLoginBtnTemplatePath();

    public function getApi();

    public function createUser($rawUser);
}
