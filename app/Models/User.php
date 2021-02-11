<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $organization_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \App\Models\UserInformation|null $info
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Role $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];
    protected $hidden = ['password'];
    protected $with = ['info', 'organization'];


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    public function groups()
    {
        return $this->belongsTo(Group::class);
    }

    public function school_class(){
        return $this->hasOne(SchoolClass::class,"class_teacher_id","id");
    }

    public function info()
    {
        return $this->hasOne(UserInformation::class, "id", "id");
    }

    public static function revokeAccessAndRefreshTokens($tokenId) {
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }


    public static  function createUserWithRelations($login,$password,$org_id,$row,$group_id)
    {

        if(SchoolClass::where("group",$row->get("group"))->where("subgroup",$row->get("group_number"))->exists()){
            $school_class_id=SchoolClass::where(["group"=>$row->get("group"),"subgroup"=>$row->get("group_number")])->first()->id;
        }else{
            $school_class =  SchoolClass::create(["group"=>$row->get("group"),"subgroup"=>$row->get("group_number")]);
            $school_class_id = $school_class->id;
        }


        $user = User::create([
            "username" => $login,
            "password" => $password,
            "organization_id" => $org_id,
            "school_class_id"=>$school_class_id,
            "group_id"=>$group_id
        ]);




        $user->info()->save(UserInformation::create([
            "id" => $user->id,
            "firstname" => $row->get("firstname"),
            "middlename" => $row->get("middlename"),
            "lastname" => $row->get("lastname"),
            "medical_policy"=>$row->get("policy")
        ]));

        return $user;
    }
}
