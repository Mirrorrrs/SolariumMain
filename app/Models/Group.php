<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Group
 *
 * @property int                          $id
 * @property string                       $name
 * @property int                          $organization_id
 * @property string                       $human_name
 * @property string|null                  $description
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null                $permissions_count
 * @property-read Collection|User[]       $users
 * @property-read int|null                $users_count
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereDescription($value)
 * @method static Builder|Group whereHumanName($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereOrganizationId($value)
 * @mixin Eloquent
 */
class Group extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];


    public static function checkPermission($mask, $permission)
    {
        $mask = decbin($mask);
        $role = collect(config("permissions"))->get($permission);
        if (($mask & (1 << $role))) {
            return true;
        }
        return false;
    }

    public function users()
    {
        return $this->HasMany(User::class);
    }
}
