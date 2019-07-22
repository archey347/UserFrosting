<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Migrations\v440;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

/**
 * Account Identity Providers migration.
 * Manages user account identity provider information.
 * Version 4.4.0.
 *
 * See https://laravel.com/docs/5.8/migrations#tables
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class AccountIdentityProvidersTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('account_identity_providers')) {
            $this->schema->create('account_identity_providers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->comment('The id of the user.');
                $table->string('idp_type');
                $table->string('idp_slug');
                $table->json('options');
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->drop('account_identity_providers');
    }
}
