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
 * ExternalIdp Class.
 *
 * Represents an External Identity Provider object as stored in the database.
 *
 * @author Archey Barrell
 * @author Amos Folz
 *
 * @property string slug
 */
class ExternalIdp extends Model
{
    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'external_idps';

    protected $fillable = [
        'slug',
    ];

    /**
     * @var bool Enable timestamps for this class.
     */
    public $timestamps = true;

    /**
     * Lazily load a collection of Users which belong to this group.
     */
    public function users()
    {
        /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = static::$ci->classMapper;

        return $this->belongsToMany($classMapper->getClassMapping('user'), 'users_external_idps', 'user_id', 'external_idps_id')
                    ->withTimestamps()
                    ->using('UserExternalIdp');
    }
}
