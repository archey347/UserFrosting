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
use UserFrosting\Sprinkle\Core\Facades\Debug;

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
    protected function verifyDatabaseIdentityProviders()
    {
        $collection = $this->getIdentityProviders();

        $results = [];

        $collection->each(function ($item, $key) use (&$results) {
            if ($this->classMapper->staticMethod('identity_provider', 'where', 'slug', "$key")->first()) {
                $results['exists'][] = $key;
            } else {
                $results['missing'][] = $key;
            }
        });
        //  Debug::debug(print_r($results, true));

        return $results;
    }

    /**
     * WWrites Identity Providers configuration to database.
     *
     * @return [type] [description]
     */
    public function writeDatabaseIdentityProviders()
    {
        $identityProviders = $this->verifyDatabaseIdentityProviders();

        $missing = $identityProviders['missing'];

        foreach ($missing as $slug) {
            $identityProvider = $this->classMapper->createInstance('identity_provider', ['slug' => $slug]);
            $identityProvider->slug = $slug;
            $identityProvider->save();
        }
    }
}
