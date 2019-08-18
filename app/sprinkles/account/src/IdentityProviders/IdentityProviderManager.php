<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\IdentityProviders;

/**
 * Identity Provider manager.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class IdentityProviderManager
{
    /**
     * @var Config
     */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getPrimaryIdp($slug)
    {
    }

    protected function checkDatabaseIdentityProviders()
    {
        $configured = $this->getIdentityProviders();
    }

    /**
     * Returns a collection of Identity Providers configurations sorted by priority.
     *
     * @return Collection
     */
    protected function getIdentityProviders()
    {
        $config = $this->config['identity_providers'];

        return collect($config)->map(function ($row) {
            return (object) $row;
        })->sortBy('priority');
    }
}
