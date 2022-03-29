<?php

namespace App\Models;

use App\Helpers\Classes\AuthHelper;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class UserType
 * @package App\Models\UserType
 * @property string title
 * @property string code
 * @property int row_status
 * @property int default_role_id
 * @property-read Role $role
 * @property-read Collection $users
 * @method static UserType|Builder authUserWiseType()
 */
class UserType extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait;

    protected $guarded = ['id'];
    public $timestamps = false;

    const USER_TYPE_SUPER_USER_CODE = '1';
    const USER_TYPE_SYSTEM_USER_CODE = '2';
    const USER_TYPE_INSTITUTE_USER_CODE = '3';
    const USER_TYPE_TRAINER_USER_CODE = '4';
    const USER_TYPE_BRANCH_USER_CODE = '5';
    const USER_TYPE_TRAINING_CENTER_USER_CODE = '6';
    const USER_TYPE_TRAINEE_USER_CODE = '7';

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'default_role_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_type_id', 'code');
    }

    public function scopeAuthUserWiseType($query): Builder
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        if ($authUser && $authUser->userType->code == self::USER_TYPE_INSTITUTE_USER_CODE) {
            $query->whereIn('code', [self::USER_TYPE_INSTITUTE_USER_CODE, self::USER_TYPE_BRANCH_USER_CODE, self::USER_TYPE_TRAINING_CENTER_USER_CODE, self::USER_TYPE_TRAINER_USER_CODE]);
        } elseif ($authUser && $authUser->userType->code == self::USER_TYPE_BRANCH_USER_CODE) {
            $query->whereIn('code', [self::USER_TYPE_BRANCH_USER_CODE, self::USER_TYPE_TRAINING_CENTER_USER_CODE, self::USER_TYPE_TRAINER_USER_CODE]);
        } elseif ($authUser && $authUser->userType->code == self::USER_TYPE_TRAINING_CENTER_USER_CODE) {
            $query->whereIn('code', [self::USER_TYPE_TRAINING_CENTER_USER_CODE, self::USER_TYPE_TRAINER_USER_CODE]);
        } elseif ($authUser && $authUser->userType->code == self::USER_TYPE_TRAINER_USER_CODE) {
            $query->whereIn('code', [self::USER_TYPE_TRAINER_USER_CODE]);
        }

        return $query;
    }
}


