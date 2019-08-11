<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Bakery;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserFrosting\Sprinkle\Core\Twig\CacheHelper;
use UserFrosting\System\Bakery\BaseCommand;
use UserFrosting\Sprinkle\Account\Database\Models\PrimaryIdp;
use UserFrosting\Sprinkle\Account\Database\Models\ExternalIdp;
use UserFrosting\Sprinkle\Core\Facades\Debug;

/**
 * ClearCache CLI Command.
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class AuthenticateStatusCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('authenticate:status')
             ->setDescription('Writes system configuration files authenticator values into the authentication database tables.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title('Writing Authenticator Configuration Values');

        $config = $this->ci->config;

        Debug::debug(print_r(get_class_methods($config), true));

        $test = $config->has('identity_providers.primary.databases');

        Debug::debug(print_r(get_class_methods($test), true));
        Debug::debug(print_r($test, true));

        $primary = $config['primary'];

        $external = $config['external'];

        $secondary = $config['secondary'];

        $primaryCollection = PrimaryIdp::all();

        $externalCollection = ExternalIdp::all();
        /*
                Debug::debug(print_r($primaryCollection, true));
                Debug::debug(print_r($externalCollection, true));
        */
        $configurations = [];

        if (!(ExternalIdp::all())) {
            print_r('it is not null');
        }
    }

    protected function checkPrimary(string $string)
    {
        $test = PrimaryIdp::where('slug', "$string")->get();
        //  Debug::debug(print_r($test, true));

        return $test;
    }

    protected function checkExternal(string $string)
    {
        $testing = ExternalIdp::where('slug', "$string")->get();

        return $testing;
    }

    /**
     * Read authentication configuration from configuration files.
     */
    protected function getConfigFromFiles()
    {
        $this->ci->cache->flush();
    }

    /**
     * Read authentication configuration from database.
     *
     * @return array true/false if operation is successfull
     */
    protected function getConfigFromDatabase()
    {
        $cacheHelper = new CacheHelper($this->ci);

        return $cacheHelper->clearCache();
    }

    /**
     * Clear the Router cache data file.
     *
     * @return bool true/false if operation is successfull
     */
    protected function clearRouterCache()
    {
        return $this->ci->router->clearCache();
    }
}
