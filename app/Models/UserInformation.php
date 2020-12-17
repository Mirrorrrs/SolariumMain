<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserInformation
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $middlename
 * @property string|null $lastname
 * @property string|null $birthdate
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $avatar
 * @property int|null $vk_id
 * @property string|null $vk_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereVkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInformation whereVkToken($value)
 * @mixin \Eloquent
 */
class UserInformation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'userinfo';
}
