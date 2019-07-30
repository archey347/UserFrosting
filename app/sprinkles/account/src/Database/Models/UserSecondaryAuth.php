<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * UserSecondaryAuth Class.
 *
 * Represents a User Secondary Authentication object as stored in the database.
 *
 * @author Archey Barrell
 * @author Amos Folz
 *
 * @property string slug
 */
class UserSecondaryAuth extends Pivot
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'users_secondary_auths';

    protected $fillable = [
        'default',
        'options',
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;
}
