<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\IdentityProviders;

use UserFrosting\Sprinkle\Account\Database\Models\IdentityProvider;

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

    /**
     * Verifies that each Identity Provider in configuration files has a corresponding record in database.
     *
     * @return array
     */
    protected function verifyDatabaseIdentityProviders()
    {
        $collection = $this->getIdentityProviders();

        $results = [];

        $collection->each(function ($item, $key) use (&$results) {
            if (IdentityProvider::where('slug', $key)->first()) {
                $results['exists'][] = $key;
            } else {
                $results['missing'][] = $key;
            }
        });

        return $results;
    }
}
