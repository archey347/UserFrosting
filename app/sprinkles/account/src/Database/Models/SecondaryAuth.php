<?php

/*
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

/**
 * SecondaryAuth Class.
 *
 * Represents a Secondary Authentication object as stored in the database.
 *
 * @author Archey Barrell
 * @author Amos Folz
 *
 * @property string slug
 */
class SecondaryAuth extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'primary_idps';

    protected $fillable = [
        'slug',
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Lazily load a collection of Users which belong to this secondary authentication method.
     */
    public function users()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsToMany($classMapper->getClassMapping('user'), 'users_secondary_auths', 'user_id', 'secondary_auths_id')
                    ->withTimestamps()
                    ->using('UserSecondaryAuth');
    }
}
