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

        $primary = $config['primary'];

        $external = $config['external'];

        $secondary = $config['secondary'];
        /*
                print_r($primary);
                print_r($external);
                print_r($secondary);
        */

        $configurations = [];

        foreach ($primary as $key => $value) {
            if (!$this->checkPrimary($key)) {
                $idp = new PrimaryIdp();
                $idp->slug = $key;
                $idp->save();
                $configurations['new'][] = $key;
            } else {
                $configurations['exists'][] = $key;
            }
        }

        Debug::debug(print_r($configurations, true));

        foreach ($external as $key => $value) {
            //      print_r($key);
            $test = $this->checkExternal($key);
            //      print_r($test);
        }
    }

    protected function checkPrimary(string $string)
    {
        return PrimaryIdp::where('slug', "$string")->first();
    }

    protected function checkExternal(string $string)
    {
        return ExternalIdp::where('slug', "$string")->first();
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
