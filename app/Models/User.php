<?php

namespace App\Models;

use App\Helpers\Classes\AuthHelper;
use App\Traits\AuthenticatableUser;
use App\Traits\LocDistrictBelongsToRelation;
use App\Traits\LocDivisionBelongsToRelation;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

/**
 * App\Models\User
 *
 * @property int id
 * @property int user_type_id
 * @property int|null role_id
 * @property string name
 * @property string|null email
 * @property string password
 * @property string|null remember_token
 * @property string|null profile_pic
 * @property int loc_district_id
 * @property int loc_division_id
 * @property int institute_id
 * @property int training_center_id
 * @property int branch_id
 * @property-read Collection|UsersPermission[] activePermissions
 * @property-read int|null active_permissions_count
 * @property-read Collection|UsersPermission[] inActivePermissions
 * @property-read int|null in_active_permissions_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] notifications
 * @property-read int|null notifications_count
 * @property-read Role|null role
 * @property-read UserType userType
 * @property-read Institute institute
 * @property-read Collection|Role[] roles
 * @property-read int|null roles_count
 * @property-read Batch|null trainerBatches
 */
class User extends AuthBaseModel implements MustVerifyEmail
{
    use AuthenticatableUser, LocDistrictBelongsToRelation, LocDivisionBelongsToRelation, ScopeAclTrait, ScopeRowStatusTrait;

    const USER_TYPE_SUPER_USER_CODE = '1';
    const USER_TYPE_SYSTEM_USER_CODE = '2';
    const USER_TYPE_INSTITUTE_USER_CODE = '3';
    const USER_TYPE_TRAINER_USER_CODE = '4';
    const USER_TYPE_BRANCH_USER_CODE = '5';
    const USER_TYPE_TRAINING_CENTER_USER_CODE = '6';

    const DEFAULT_PROFILE_PIC = 'users/default.jpg';
    const PROFILE_PIC_FOLDER_NAME = 'users';

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'userType',
    ];

    /**
     * ---------------------------------------
     *  User Relationship start              *
     * ---------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    /**
     * ---------------------------------------
     *  This related method start            *
     * ---------------------------------------
     */
    public function profilePicIsDefault(): bool
    {
        return $this->profile_pic === self::DEFAULT_PROFILE_PIC;
    }

    public function isSystemUser(): bool
    {
        return $this->userType->code === self::USER_TYPE_SYSTEM_USER_CODE;
    }

    public function isSuperUser(): bool
    {
        return $this->userType->code === self::USER_TYPE_SUPER_USER_CODE;
    }

    public function isInstituteLevelUser(): bool
    {
        return $this->userType->code === self::USER_TYPE_INSTITUTE_USER_CODE;
    }

    public function isBranchLevelUser(): bool
    {
        return $this->userType->code === self::USER_TYPE_BRANCH_USER_CODE;
    }

    public function isTrainingCenterLevelUser(): bool
    {
        return $this->userType->code === self::USER_TYPE_TRAINING_CENTER_USER_CODE;
    }

    public function isTrainer(): bool
    {
        return $this->userType->code === self::USER_TYPE_TRAINER_USER_CODE;
    }

    public function isUserBelongsToInstitute(): bool
    {
        return $this->userType->code === self::USER_TYPE_INSTITUTE_USER_CODE ||
            $this->userType->code === self::USER_TYPE_TRAINER_USER_CODE ||
            $this->userType->code === self::USER_TYPE_BRANCH_USER_CODE ||
            $this->userType->code === self::USER_TYPE_TRAINING_CENTER_USER_CODE;
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    /**
     * @return HasMany
     */
    public function trainerAcademicQualifications(): HasMany
    {
        return $this->hasMany(TrainerAcademicQualification::class, 'trainer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function trainerExperiences(): HasMany
    {
        return $this->hasMany(TrainerExperience::class, 'trainer_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function trainerPersonalInformation(): HasOne
    {
        return $this->hasOne(TrainerPersonalInformation::class, 'trainer_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function trainerBatches(): BelongsToMany
    {
        return $this->belongsToMany(
            Batch::class,
            'trainer_batches',
            'user_id',
            'batch_id');
    }

    /**
     * @return HasMany
     */
    public function getTodayRoutineSlot(): HasMany
    {
        return $this->hasMany(RoutineSlot::class);
    }

    /**
     * @param Builder $query
     * @param string|null $alias
     * @return Builder
     */
    public function scopeAcl(Builder $query, string $alias = null): Builder
    {
        if (empty($alias)) {
            $alias = $this->getTable() . '.';
        }

        if (!AuthHelper::checkAuthUser()) {
            return $query;
        }

        $authUser = AuthHelper::getAuthUser();

        if ($authUser->isUserBelongsToInstitute()) {
            $query->where($alias . 'institute_id', $authUser->institute_id);
        }

        if ($authUser->isTrainer()) {
            $query->where($alias . 'id', $authUser->id);
        }

        $userTypes = UserType::authUserWiseType()->pluck('id');
        $query->whereIn('user_type_id', $userTypes);

        return $query;
    }
}
