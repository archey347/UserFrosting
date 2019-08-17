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
use UserFrosting\System\Bakery\BaseCommand;
use UserFrosting\Sprinkle\Account\Database\Models\PrimaryIdp;
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
        $this->io->title('Authenticator Configuration Status');

        $config = $this->ci->config['identity_providers'];

        $test = $this->check();

        Debug::debug(print_r($this->result, true));
    }

    protected function check()
    {
        $collection = $this->getPrimaryFromConfigFiles();

        $collection->each(function ($item, $key) {
            if (PrimaryIdp::where('slug', $key)->first()) {
                $this->result['exists'][] = $key;
            } else {
                $this->result['missing'][] = $key;
            }
        });
    }

    /**
     * Returns a collection of Primary Identity Providers configurations sorted by priority.
     *
     * @return Collection
     */
    protected function getPrimaryFromConfigFiles()
    {
        $config = $this->ci->config['identity_providers']['primary'];

        return collect($config)->map(function ($row) {
            return (object) $row;
        })->sortBy('priority');
    }

    /**
     * Returns a collection of External Identity Providers configurations.
     *
     * @return Collection
     */
    protected function getExternalFromConfigFiles()
    {
        $config = $this->ci->config['identity_providers']['external'];

        return collect($config)->map(function ($row) {
            return (object) $row;
        })->sortBy('priority');
    }
}
