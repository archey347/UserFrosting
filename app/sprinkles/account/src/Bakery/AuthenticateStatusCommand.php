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

        $config = $this->ci->config['identity_providers'];

        $primaryCollection = $this->getPrimary();
        //    Debug::debug(print_r($primaryCollection, true));
        $externalCollection = $this->getExternal();

        //  Debug::debug(print_r($primaryCollection, true));

        $primary = $config['primary'];

        $external = $config['external'];

        $secondary = $config['secondary'];

        $test = collect($primary)->map(function ($row) {
            return (object) $row;
        });

        Debug::debug(print_r($test, true));

        $test->each(function ($item) {
            print_r($item->class_name);
        });
    }

    protected function getPrimary()
    {
        return PrimaryIdp::all();
    }

    protected function getExternal()
    {
        return ExternalIdp::all();
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
