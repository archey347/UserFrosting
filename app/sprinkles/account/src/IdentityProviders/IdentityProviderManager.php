<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\IdentityProviders;

use UserFrosting\Sprinkle\Core\Util\ClassMapper;
use UserFrosting\Support\Repository\Repository as Config;

/**
 * Identity Provider manager.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class IdentityProviderManager
{
    /**
     * @var ClassMapper
     */
    protected $classMapper;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config, ClassMapper $classMapper)
    {
        $this->config = $config;

        $this->classMapper = $classMapper;
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
    public function verifyDatabaseIdentityProviders()
    {
        $collection = $this->getIdentityProviders();

        $success = true;

        $collection->each(function ($item, $key) use (&$success) {
            if (!$this->classMapper->staticMethod('identity_provider', 'where', 'slug', "$key")->first()) {
                $success = false;

                return $success;
            }
        });

        return $success;
    }

    /**
     * Get Identity Providers in configuration files that do not have a corresponding record in the database.
     *
     * @return array
     */
    public function getMissingDatabaseIdentityProviders(): array
    {
        $collection = $this->getIdentityProviders();

        $missing = [];

        $collection->each(function ($item, $key) use (&$missing) {
            if (!$this->classMapper->staticMethod('identity_provider', 'where', 'slug', "$key")->first()) {
                $missing[] = $key;
            }
        });

        return $missing;
    }

    /**
     * Get Identity Providers in configuration files that have a corresponding record in the database.
     *
     * @return array
     */
    public function getExistingDatabaseIdentityProviders(): array
    {
        $collection = $this->getIdentityProviders();

        $exists = [];

        $collection->each(function ($item, $key) use (&$exists) {
            if ($this->classMapper->staticMethod('identity_provider', 'where', 'slug', "$key")->first()) {
                $exists[] = $key;
            }
        });

        return $exists;
    }

    /**
     * Writes Identity Providers configuration to database.
     *
     * @return [type] [description]
     */
    public function writeDatabaseIdentityProviders()
    {
        $identityProviders = $this->getMissingDatabaseIdentityProviders();

        foreach ($identityProviders as $slug) {
            $identityProvider = $this->classMapper->createInstance('identity_provider', ['slug' => $slug]);
            $identityProvider->save();
        }
    }
}
