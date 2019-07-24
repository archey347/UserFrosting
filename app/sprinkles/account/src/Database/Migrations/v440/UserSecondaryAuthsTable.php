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
 * Users Multi-factor authentication migration.
 * Manages user account secondary authentication information.
 * Version 4.4.0.
 *
 * See https://laravel.com/docs/5.8/migrations#tables
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class UserSecondaryAuthsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public static $dependencies = [
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v400\UsersTable',
        '\UserFrosting\Sprinkle\Account\Database\Migrations\v440\SecondaryAuthsTable',
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('users_secondary_auths')) {
            $this->schema->create('users_secondary_auths', function (Blueprint $table) {
                $table->integer('secondary_auths_id')->unsigned()->comment('The id of the authentication method.');
                $table->integer('user_id')->unsigned()->comment('The id of the user.');
                $table->boolean('default');
                $table->json('options');
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('secondary_auths_id')->references('id')->on('external_idps');
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->drop('users_secondary_auths');
    }
}
