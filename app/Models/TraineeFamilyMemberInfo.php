<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class TraineeFamilyMemberInfo
 * @package App\Models
 * @property int $trainee_id
 * @property string|null $member_name
 * @property string|null mobile
 * @property string|null educational_qualification
 * @property string|null relation_with_trainee
 * @property string|null relation
 * @property int|null gender
 * @property string|null occupation
 * @property string|null nid
 * @property Carbon|null date_of_birth
 */
class TraineeFamilyMemberInfo extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait, ScopeRowStatusTrait;

    protected $table = "trainees_family_member_info";
    protected $guarded = ['id'];

    /**
     * Guardian options
     */
    public const GUARDIAN_FATHER = 1;
    public const GUARDIAN_MOTHER = 2;
    public const GUARDIAN_OTHER = 3;

    public static function getGuardianOptions(): array
    {
        return [
            self::GUARDIAN_FATHER => 'Father',
            self::GUARDIAN_MOTHER => 'Mother',
            self::GUARDIAN_OTHER => 'Other',
        ];
    }

    public function getGuardian(): string
    {
        $guardian = '';

        $guardianOptionArray = self::getGuardianOptions();

        if ($this->relation_with_trainee == self::GUARDIAN_OTHER) {
            return $this->relation;
        } elseif (!empty($guardianOptionArray[$this->relation_with_trainee])) {
            return $guardianOptionArray[$this->relation_with_trainee];
        } elseif ($this->relation_with_trainee) {
            return $this->relation_with_trainee;
        } else {
            return $guardian;
        }
    }


    public function currentMaritalStatus(): string
    {
        $maritalStatus = '';

        $maritalStatusArray = self::getMaritalOptions();

        if (empty($maritalStatusArray[$this->marital_status])) return $maritalStatus;


        $maritalStatus = $maritalStatusArray[$this->marital_status];

        return $maritalStatus;
    }

    public function getUserReligion(): string
    {
        $userReligion = '';

        $religionArray = self::getReligionOptions();

        if (empty($religionArray[$this->religion])) return $userReligion;

        return $religionArray[$this->religion];
    }


    public function getUserGender(): string
    {
        $userGender = '';

        $SexArray = self::getSexOptions();

        if (empty($SexArray[$this->gender])) return $userGender;

        return $SexArray[$this->gender];
    }

    public function getTraineeFreedomFighterStatus(): string
    {
        $freedomFighterStatus = '';

        $freedomFighterStatusArray = self::getFreedomFighterStatusOptions();

        if (empty($freedomFighterStatusArray[$this->freedom_fighter_status])) return $freedomFighterStatus;

        return $freedomFighterStatusArray[$this->freedom_fighter_status];
    }


    /**
     * marital statuses
     */
    public const MARITAL_STATUS_MARRIED = 1;
    public const MARITAL_STATUS_SINGLE = 2;

    /**
     * freedom fighter options
     */
    public const FREEDOM_FIGHTER_SON = 1;
    public const FREEDOM_FIGHTER_GRANDSON = 2;
    public const FREEDOM_FIGHTER_NOT = 3;


    public static function getFreedomFighterStatusOptions(): array
    {
        return
            [
                self::FREEDOM_FIGHTER_SON => __('Freedom fighter son'),
                self::FREEDOM_FIGHTER_GRANDSON => __('Freedom fighter grandson'),
                self::FREEDOM_FIGHTER_NOT => __('Non freedom fighter'),
            ];
    }


    /**
     * religion options
     */
    public const RELIGION_ISLAM = 1;
    public const RELIGION_HINDU = 2;
    public const RELIGION_CHRISTIAN = 3;
    public const RELIGION_BUDDHIST = 4;
    public const RELIGION_JAIN = 5;
    public const RELIGION_OTHERS = 6;

    /**
     * gender statuses
     */
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDER_OTHER = 3;

    /**
     * Hijra
     */
    public const GENDER_HERMAPHRODITE = 4;
    /**
     * Who transform gender
     */
    public const GENDER_TRANSGENDER = 5;


    /**
     * get sex options
     * @return array
     */
    public static function getSexOptions(): array
    {
        return [
            self::GENDER_MALE => __('Male'),
            self::GENDER_FEMALE => __('Female'),
            self::GENDER_HERMAPHRODITE => __('Hermaphrodite'),
            self::GENDER_TRANSGENDER => __('Transgender'),
        ];
    }

    public static function getMaritalOptions(): array
    {
        return [
            self::MARITAL_STATUS_MARRIED => __('Married'),
            self::MARITAL_STATUS_SINGLE => __('Single')
        ];
    }

    public static function getReligionOptions(): array
    {
        return [
            self::RELIGION_ISLAM => __('Islam'),
            self::RELIGION_HINDU => __('Hindu'),
            self::RELIGION_CHRISTIAN => __('Christian'),
            self::RELIGION_BUDDHIST => __('Buddhist'),
            self::RELIGION_OTHERS => __('Others'),
        ];
    }


    /**
     * physical disability status
     */
    public const PHYSICALLY_DISABLE_YES = 1;
    public const PHYSICALLY_DISABLE_NOT = 2;


    /**
     * physical disability options
     */
    public const PHYSICAL_DISABILITY_BRAIN_INJURY = 1;
    public const PHYSICAL_DISABILITY_EPILEPSY = 2;
    public const PHYSICAL_DISABILITY_CYSTIC_FIBROSIS = 3;
    public const PHYSICAL_DISABILITY_OTHERS = 4;

    public static function getPhysicalDisabilityOptions(): array
    {
        return [
            self::PHYSICAL_DISABILITY_BRAIN_INJURY => __('Brain injury'),
            self::PHYSICAL_DISABILITY_EPILEPSY => __('Epilepsy'),
            self::PHYSICAL_DISABILITY_CYSTIC_FIBROSIS => __('Cystic fibrosis'),
            self::PHYSICAL_DISABILITY_OTHERS => __('Others'),
        ];

    }


    public function trainee(): BelongsTo
    {
        return $this->BelongsTo(Trainee::class);
    }

}
