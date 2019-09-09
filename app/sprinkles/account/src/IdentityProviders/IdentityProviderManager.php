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
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\PrimaryIdentityProviderInterface;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\ExternalIdentityProviderInterface;

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

    /**
     * Get a primary identity provider object
     * @param string $slug The slug of the Identity Provider to get
     *
     * @return PrimaryIdentityProviderInterface
     */
    public function getPrimaryIdentityProvider(String $slug): PrimaryIdentityProviderInterface
    {

        // Attempt to find the IDP
        $identityProviders = $this->getIdentityProviders('primary');

        // Get the config
        $config = $identityProviders->get($slug);

        // Fail if not found
        if (!$config) {
            new \Exception("A primary identity provider with slug '$slug' doesn't exist");
        }

        // Get the class name
        $className = $config->class_name;

        // Check the class exists
        if (!class_exists($className)) {
            new \Exception("The primary identity provider with slug '$slug' has an invalid class name");
        }

        // Create the object
        $identityProvider = new $className($config);

        return $identityProvider;
    }

    /**
     * Get an external identity provider object
     * @param string $slug The slug of the IDP to get
     *
     * @return ExternalIdentityProviderInterface
     */
    public function getExternalIdentityProvider(String $slug): ExternalIdentityProviderInterface
    {
        // Attempt to find the IDP
        $identityProviders = $this->getIdentityProviders('external');

        // Get the config
        $config = $identityProviders[$slug];

        // Fail if not found
        if (!$config) {
            new \Exception("An external identity provider with slug '$slug' doesn't exist");
        }

        // Get the class name
        $className = $config->class_name;

        // Check the class exists
        if (!class_exists($className)) {
            new \Exception("The external identity provider with slug '$slug' has an invalid class name");
        }

        // Create the object
        $identityProvider = new $className($config);

        return $identityProvider;
    }

    /**
     * Returns a collection of Identity Providers configurations sorted by priority.
     *
     * @param string $type Specific Identity Provider type to get.
     *
     * @return Collection
     */
    public function getIdentityProviders($type = 'null')
    {
        $config = $this->config['identity_providers'];

        $collection = collect($config)->map(function ($row) {
            return (object) $row;
        })->sortBy('priority');

        if ($type) {
            return $collection->where('type', "$type");
        }

        return $collection;
    }

    /**
     * Verifies that each Identity Provider in configuration files has a corresponding record in database.
     *
     * @return bool True if each Identity Provider in configuration files has a corresponding record in database, false otherwise.
     */
    public function verifyDatabaseIdentityProviders() :bool
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

    /**
     * Get external identity provider Slugs in order of priority
     */
    public function getPrimaryIdentityProviderSlugList()
    {
        $identityProviders = $this->getIdentityProviders('primary');

        return $identityProviders->keys();
    }

    /**
     * Get primary identity provider slugs in order of priority
     */
    public function getExternalIdentityProviderSlugList()
    {
        $identityProviders = $this->getIdentityProviders('external');

        return $identityProviders->keys();
    }
}
