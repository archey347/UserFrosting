<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Migrations\v500;

use Illuminate\Database\Schema\Blueprint;
use UserFrosting\Sprinkle\Core\Database\Migration;

/**
 * Secondary Authentication migration.
 * Manages secondary authentication (2FA MFA) information.
 * Version 4.4.0.
 *
 * See https://laravel.com/docs/5.8/migrations#tables
 *
 * @author Archey Barrell
 * @author Amos Folz
 */
class SecondaryAuthsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public static $dependencies = [
    ];

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->schema->hasTable('secondary_auths')) {
            $this->schema->create('secondary_auths', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug')->unique();
                $table->timestamps();

                $table->engine = 'InnoDB';
                $table->collation = 'utf8_unicode_ci';
                $table->charset = 'utf8';
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->drop('secondary_auths');
    }
}
