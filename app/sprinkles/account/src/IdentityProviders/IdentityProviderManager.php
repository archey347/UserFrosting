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
     * Checks each configured Identity Provider to see if there is a corresponding record in database.
     * Will mark each entry 0 if it doesn't exist, 1 otherwise.
     *
     * @return array [description]
     */
    protected function verifyDatabaseIdentityProviders()
    {
        $collection = $this->getIdentityProviders();

        $results = [];

        $collection->each(function ($item, $key) use (&$results) {
            if (IdentityProvider::where('slug', $key)->first()) {
                $results[$key] = 1;
            } else {
                $results[$key] = 0;
            }
        });

        return $results;
    }
}
