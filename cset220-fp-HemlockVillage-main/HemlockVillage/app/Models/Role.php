<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\ModelHelper;

class Role extends Model
{
    protected $fillable = [
        "role",
        "access_level"
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @param string $role
     * @return int|null The id; null if the record can not be found
     */
    public static function getId($role)
    {
        return ModelHelper::getId(Role::class, "role", $role);
    }
}
