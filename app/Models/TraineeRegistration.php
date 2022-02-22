<?php

namespace App\Models;

use App\Helpers\Classes\Helper;
use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TraineeRegistration
 * @package App\Models
 * @property string|null trainee_registration_no
 * @property int trainee_id
 * @property int publish_course_id
 * @property int|null recommended_by_organization
 * @property string|null recommended_org_name
 * @property int|null current_employment_status
 * @property int|null year_of_experience
 * @property int|null personal_monthly_income
 * @property int|null have_family_own_house
 * @property int|null have_family_own_land
 * @property string|null student_signature_pic
 */
class TraineeRegistration extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait, ScopeRowStatusTrait;

    protected $guarded = ['id'];

    public const HAVE_FAMILY_OWN_HOUSE = 1;
    public const HAVE_FAMILY_OWN_LAND = 1;
    public const  RECOMMENDED_BY_ORGANIZATION = 1;


    public const CURRENT_EMPLOYMENT_STATUS_YES = 1;
    public const CURRENT_EMPLOYMENT_STATUS_NO = 2;

    public static function getTraineeCurrentEmploymentStatusOptions(): array
    {
        return [
            self::CURRENT_EMPLOYMENT_STATUS_YES => __('Yes'),
            self::CURRENT_EMPLOYMENT_STATUS_NO => __('No'),
        ];
    }


    public function setTraineeRegistrationNumber(): string
    {
        if (empty($this->trainee_registration_no)) {
            $this->trainee_registration_no = Helper::randomPassword(10, true);
        }

        return $this->trainee_registration_no;
    }

    public function getTraineeCurrentEmploymentStatus(): string
    {
        $employmentStatus = 'Not specified';
        $employmentStatusArray = self::getTraineeCurrentEmploymentStatusOptions();
        if (empty($employmentStatusArray[$this->current_employment_status])) return $employmentStatus;

        return $employmentStatusArray[$this->current_employment_status];
    }
}
