<?php

namespace App\Models;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\Helper;
use App\Traits\AuthenticatableUser;
use App\Traits\LocDistrictBelongsToRelation;
use App\Traits\LocDivisionBelongsToRelation;
use App\Traits\LocUpazilaBelongsToRelation;
use App\Traits\ScopeRowStatusTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Trainee
 * @package App\Models
 * @property string name
 * @property string $mobile
 * @property int user_id
 * @property int loc_division_id
 * @property int loc_district_id
 * @property int loc_upazila_id
 * @property int gender
 * @property int disable_status
 * @property int|null ethnic_group
 * @property Carbon date_of_birth
 * @property int|null recommended_by_organization
 * @property int|null recommended_org_name
 * @property int|null current_employment_status
 * @property int|null year_of_experience
 * @property int|null personal_monthly_income
 * @property int|null have_family_own_house
 * @property int|null have_family_own_land
 * @property int|null number_of_siblings
 * @property string|null student_signature_pic
 * @property string|null student_pic
 * @property string password
 * @property-read TraineeFamilyMemberInfo familyMemberInfo
 * @property-read  TraineeAcademicQualification academicQualifications
 * @property-read  TraineeBatch batches
 */
class Trainee extends AuthBaseModel
{
    use AuthenticatableUser;

    use HasFactory, ScopeRowStatusTrait, LocDivisionBelongsToRelation, LocDistrictBelongsToRelation, LocUpazilaBelongsToRelation;

    protected $guarded = ['id'];
    protected $hidden = ['password'];

    const PROFILE_PIC_FOLDER_NAME = 'trainee';
    const DEFAULT_PROFILE_PIC = 'trainees/default.jpg';


    public static function findOrFail(int $id) {
        $trainee = Trainee::where('user_id', $id)->first();
        if ($trainee) {
            return $trainee;
        }else {
            abort('404');
        }
    }

    public static function getTraineeByAuthUser() {
        $authUser = AuthHelper::getAuthUser();
        return Trainee::findOrFail($authUser->id);
    }
    /**
     * @return string
     */
    public static function getUniqueAccessKey(): string
    {
        while (true) {
            $value = Helper::randomPassword(10, true);
            if (!Trainee::where('access_key', $value)->count()) {
                break;
            }
        }

        return $value;
    }

    /**
     * Hijra
     */
    public const GENDER_HERMAPHRODITE = 4;
    /**
     * Who transform gender
     */
    public const GENDER_TRANSGENDER = 5;

    /** Status Code */
    public const TRUE_LABEL = "TRUE";
    public const FALSE_LABEL = "FALSE";
    public const TRUE = 1;
    public const FALSE = 2;
    public const STATUS_CODE_WITH_LABEL = [
        self::TRUE_LABEL => self::TRUE,
        self::FALSE_LABEL => self::FALSE
    ];

    /**
     * ethnic group
     */
    public const ETHNIC_GROUP_YES = self::TRUE;
    public const ETHNIC_GROUP_NO = self::FALSE;

    /** Guardian Status Code */

    public const GUARDIAN_FATHER_LABEL = "FATHER";
    public const GUARDIAN_MOTHER_LABEL = "MOTHER";
    public const GUARDIAN_OTHER_LABEL = "OTHER";

    public const GUARDIAN_STATUS = [
        "FATHER" => TraineeFamilyMemberInfo::GUARDIAN_FATHER,
        "MOTHER" => TraineeFamilyMemberInfo::GUARDIAN_MOTHER,
        "OTHER" => TraineeFamilyMemberInfo::GUARDIAN_OTHER,
    ];


    /** Gender Information */
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDER_OTHERS = 3;

    public const GENDER_STATUS = [
        "MALE" => self::GENDER_MALE,
        "FEMALE" => self::GENDER_FEMALE,
        "OTHERS" => self::GENDER_OTHERS
    ];

    /** Marital Status */
    public const IS_Marit_YES = self::TRUE;
    public const IS_Marit_NO = self::FALSE;

    /** Disability Status */

    public const IS_DISABLE_YES = self::TRUE;
    public const IS_DISABLE_NO = self::FALSE;

    /** Freedom Fighter Status */
    public const IS_FREEDOM_YES = self::TRUE;
    public const IS_FREEDOM_NO = self::FALSE;

    /** Religions Mapping  */
    public const RELIGION_ISLAM = 1;
    public const RELIGION_HINDUISM = 2;
    public const RELIGION_CHRISTIANITY = 3;
    public const RELIGION_BUDDHISM = 4;
    public const RELIGION_JUDAISM = 5;
    public const RELIGION_SIKHISM = 6;
    public const RELIGION_ETHNIC = 7;
    public const RELIGION_AGNOSTIC_ATHEIST = 8;
    public const RELIGION_OTHERS = 10;

    public const RELIGIONS = [
        "ISLAM" => self::RELIGION_ISLAM,
        "HINDUISM" => self::RELIGION_HINDUISM,
        "CHRISTIANITY" => self::RELIGION_CHRISTIANITY,
        "BUDDHISM" => self::RELIGION_BUDDHISM,
        "JUDAISM" => self::RELIGION_JUDAISM,
        "ETHNIC" => self::RELIGION_ETHNIC,
        "AGNOSTIC_ATHEIST" => self::RELIGION_AGNOSTIC_ATHEIST,
        "SIKHISM" => self::RELIGION_SIKHISM,
        "OTHERS" => self::RELIGION_OTHERS
    ];

    public const EXAMINATION_JSC = 1;
    public const EXAMINATION_SSC = 2;
    public const EXAMINATION_HSC = 3;
    public const EXAMINATION_GRADUATION = 4;
    public const EXAMINATION_MASTERS = 5;
    public const EXAMINATION_OTHERS = 6;

    public const EXAMINATION_LEVELS = [
        "J.S.C" => self::EXAMINATION_JSC,
        'S.S.C' => self::EXAMINATION_SSC,
        'H.S.C' => self::EXAMINATION_HSC,
        'Graduation' => self::EXAMINATION_GRADUATION,
        'Masters' => self::EXAMINATION_MASTERS,
        'Others' => self::EXAMINATION_OTHERS
    ];

    /** JDC/JSC */
    public const JSC_EXAMINATION_JSC = 1;
    public const JDC_EXAMINATION_JDC = 2;
    public const JSC_JDC_EXAMINATIONS = [
        'JSC' => self::JSC_EXAMINATION_JSC,
        'JDC' => self::JDC_EXAMINATION_JDC
    ];

    /** SSC/DAKHIL/SSC_VOC*/
    public const SSC_EXAMINATION_SSC = 1;
    public const SSC_EXAMINATION_DAKHIL = 2;
    public const SSC_EXAMINATION_SSC_OR_DAKHIL_VOCATIONAL = 3;
    public const SSC_EXAMINATION_O_LEVEL = 4;
    public const SSC_EXAMINATION_SSC_EQUIVALENT = 5;
    public const SSC_EQUIVALENT_EXAMINATIONS = [
        "SSC" => self::SSC_EXAMINATION_SSC,
        "DAKHIL" => self::SSC_EXAMINATION_DAKHIL,
        "SSC_OR_DAKHIL_VOCATIONAL" => self::SSC_EXAMINATION_SSC_OR_DAKHIL_VOCATIONAL,
        "O_LEVEL" => self::SSC_EXAMINATION_O_LEVEL,
        "SSC_EQUIVALENT" => self::SSC_EXAMINATION_SSC_EQUIVALENT,
    ];

    /** HSC_EQUIVALENT_EXAMINATION*/

    public const HSC_EXAMINATION_HSC = 1;
    public const HSC_EXAMINATION_ALIM = 2;
    public const HSC_EXAMINATION_DIPLOMA_IN_COMMERCE = 3;
    public const HSC_EXAMINATION_DIPLOMA_IN_ENGINEERING = 4;
    public const HSC_EXAMINATION_A_LEVEL = 5;
    public const HSC_EXAMINATION_HSC_EQUIVALENT = 6;
    public const HSC_EXAMINATION_DIPLOMA_IN_NURSING_OR_MIDWIFERY = 7;
    public const HSC_EXAMINATION_HSC_VOCATIONAL = 8;
    public const HSC_EQUIVALENT_EXAMINATIONS =
        [
            "HSC" => self::HSC_EXAMINATION_HSC,
            "ALIM" => self::HSC_EXAMINATION_ALIM,
            "DIPLOMA_IN_COMMERCE" => self::HSC_EXAMINATION_DIPLOMA_IN_COMMERCE,
            "DIPLOMA_IN_ENGINEERING" => self::HSC_EXAMINATION_DIPLOMA_IN_ENGINEERING,
            "A_LEVEL" => self::HSC_EXAMINATION_A_LEVEL,
            "HSC_EQUIVALENT" => self::HSC_EXAMINATION_HSC_EQUIVALENT,
            "DIPLOMA_IN_NURSING_OR_MIDWIFERY" => self::HSC_EXAMINATION_DIPLOMA_IN_NURSING_OR_MIDWIFERY,
            "HSC_VOCATIONAL" => self::HSC_EXAMINATION_HSC_VOCATIONAL,
        ];

    /** GRADUATION_EXAMINATION_BSC */
    public const GRADUATION_EXAMINATION_BSC_ENGINEERING = 1;
    public const GRADUATION_EXAMINATION_BSC_AGRICULTURE = 2;
    public const GRADUATION_EXAMINATION_MBBS_BDS = 3;
    public const GRADUATION_EXAMINATION_HONOURS = 4;
    public const GRADUATION_EXAMINATION_PASS_COURSE = 5;
    public const GRADUATION_EXAMINATION_AMIE = 6;
    public const GRADUATION_EXAMINATION_BSC_NURSING = 7;
    public const GRADUATION_EXAMINATION_OTHERS = 8;
    public const EXAMINATION_BSC_HONOURS =
        [
            "BSC_ENGINEERING" => self::GRADUATION_EXAMINATION_BSC_ENGINEERING,
            "BSC_AGRICULTURE" => self::GRADUATION_EXAMINATION_BSC_AGRICULTURE,
            "MBBS_BDS" => self::GRADUATION_EXAMINATION_MBBS_BDS,
            "HONOURS" => self::GRADUATION_EXAMINATION_HONOURS,
            "PASS_COURSE" => self::GRADUATION_EXAMINATION_PASS_COURSE,
            "AMIE" => self::GRADUATION_EXAMINATION_AMIE,
            "BSC_NURSING" => self::GRADUATION_EXAMINATION_BSC_NURSING,
            "GRADUATION_EXAMINATION_OTHERS" => self::GRADUATION_EXAMINATION_OTHERS,
        ];

    /** MASTERS_EXAMINATION_MSC  */
    public const MASTERS_EXAMINATION_MSC_ENGINEERING = 1;
    public const MASTERS_EXAMINATION_MSC_AGRICULTURE = 2;
    public const MASTERS_EXAMINATION_MS_MEDICAL = 3;
    public const MASTERS_EXAMINATION_MASTERS = 4;
    public const MASTERS_EXAMINATION_MSC_NURSING = 5;
    public const MASTERS_EXAMINATION_OTHERS = 6;
    public const MASTERS_EXAMINATIONS =
        [
            "MSC_ENGINEERING" => self::MASTERS_EXAMINATION_MSC_ENGINEERING,
            "MSC_AGRICULTURE" => self::MASTERS_EXAMINATION_MSC_AGRICULTURE,
            "MS_MEDICAL" => self::MASTERS_EXAMINATION_MS_MEDICAL,
            "MASTERS" => self::MASTERS_EXAMINATION_MASTERS,
            "MSC_NURSING" => self::MASTERS_EXAMINATION_MSC_NURSING,
            "MASTERS_EXAMINATION_OTHERS" => self::MASTERS_EXAMINATION_OTHERS,
        ];

    /** EXAMINATION_COURSE_DURATION */
    public const EXAMINATION_COURSE_DURATION_ONE_YEAR = 1;
    public const EXAMINATION_COURSE_DURATION_TWO_YEAR = 2;
    public const EXAMINATION_COURSE_DURATION_THREE_YEAR = 3;
    public const EXAMINATION_COURSE_DURATION_FOUR_YEAR = 4;
    public const EXAMINATION_COURSE_DURATION_FIVE_YEAR = 5;
    public const EXAMINATION_COURSE_DURATION =
        [
            "COURSE_DURATION_ONE_YEAR" => self::EXAMINATION_COURSE_DURATION_ONE_YEAR,
            "COURSE_DURATION_TWO_YEAR" => self::EXAMINATION_COURSE_DURATION_TWO_YEAR,
            "COURSE_DURATION_THREE_YEAR" => self::EXAMINATION_COURSE_DURATION_THREE_YEAR,
            "COURSE_DURATION_FOUR_YEAR" => self::EXAMINATION_COURSE_DURATION_FOUR_YEAR,
            "COURSE_DURATION_FIVE_YEAR" => self::EXAMINATION_COURSE_DURATION_FIVE_YEAR,
        ];

    /**EXAMINATION_BOARD  */
    public const EXAMINATION_BOARD_DHAKA = 1;
    public const EXAMINATION_BOARD_COMILLA = 2;
    public const EXAMINATION_BOARD_RAJSHAHI = 3;
    public const EXAMINATION_BOARD_JESSOR = 4;
    public const EXAMINATION_BOARD_CHITTAGONG = 5;
    public const EXAMINATION_BOARD_BARISAL = 6;
    public const EXAMINATION_BOARD_SYLHET = 7;
    public const EXAMINATION_BOARD_DINAJPUR = 8;
    public const EXAMINATION_BOARD_MADRASAH = 9;
    public const EXAMINATION_BOARD_TECHNICAL = 10;
    public const EXAMINATION_BOARD_EDEXCEL_INTERNATIONAL = 11;
    public const EXAMINATION_BOARD_BANGLADESH_NURSING_AND_MIDWIFERY_COUNCIL = 12;
    public const EXAMINATION_BOARD_OTHERS = 13;
    public const EXAMINATION_BOARDS = [
        "EXAMINATION_BOARD_DHAKA" => self::EXAMINATION_BOARD_DHAKA,
        "EXAMINATION_BOARD_COMILLA" => self::EXAMINATION_BOARD_COMILLA,
        "EXAMINATION_BOARD_RAJSHAHI" => self::EXAMINATION_BOARD_RAJSHAHI,
        "EXAMINATION_BOARD_JESSOR" => self::EXAMINATION_BOARD_JESSOR,
        "EXAMINATION_BOARD_CHITTAGONG" => self::EXAMINATION_BOARD_CHITTAGONG,
        "EXAMINATION_BOARD_BARISAL" => self::EXAMINATION_BOARD_BARISAL,
        "EXAMINATION_BOARD_SYLHET" => self::EXAMINATION_BOARD_SYLHET,
        "EXAMINATION_BOARD_DINAJPUR" => self::EXAMINATION_BOARD_DINAJPUR,
        "EXAMINATION_BOARD_MADRASAH" => self::EXAMINATION_BOARD_MADRASAH,
        "EXAMINATION_BOARD_TECHNICAL" => self::EXAMINATION_BOARD_TECHNICAL,
        "EXAMINATION_BOARD_EDEXCEL_INTERNATIONAL" => self::EXAMINATION_BOARD_EDEXCEL_INTERNATIONAL,
        "EXAMINATION_BOARD_BANGLADESH_NURSING_AND_MIDWIFERY_COUNCIL" => self::EXAMINATION_BOARD_BANGLADESH_NURSING_AND_MIDWIFERY_COUNCIL,
        "EXAMINATION_BOARD_OTHERS" => self::EXAMINATION_BOARD_OTHERS,
    ];

    /** EXAMINATION_GROUP */
    public const EXAMINATION_GROUP_SCIENCE = 1;
    public const EXAMINATION_GROUP_HUMANITIES = 2;
    public const EXAMINATION_GROUP_BUSINESS = 3;
    public const EXAMINATION_GROUP_OTHERS = 4;
    public const EXAMINATION_GROUPS =
        [
            "EXAMINATION_GROUP_SCIENCE" => self::EXAMINATION_GROUP_SCIENCE,
            "EXAMINATION_GROUP_HUMANITIES" => self::EXAMINATION_GROUP_HUMANITIES,
            "EXAMINATION_GROUP_BUSINESS" => self::EXAMINATION_GROUP_BUSINESS,
            "EXAMINATION_GROUP_OTHERS" => self::EXAMINATION_GROUP_OTHERS,
        ];

    /** EXAMINATION_RESULT */
    public const EXAMINATION_RESULT_FIRST_DIVISION = 6;
    public const EXAMINATION_RESULT_SECOND_DIVISION = 7;
    public const EXAMINATION_RESULT_GPA_OUT_OF_FOUR = 3;
    public const EXAMINATION_RESULT_GPA_OUT_OF_FIVE = 4;
    public const EXAMINATION_RESULT_PASSED = 8;
    public const EXAMINATION_RESULT_PASSED_MBBS_BDS = 9;
    public const EXAMINATION_RESULTS =
        [
            "EXAMINATION_RESULT_FIRST_DIVISION" => self::EXAMINATION_RESULT_FIRST_DIVISION,
            "EXAMINATION_RESULT_SECOND_DIVISION" => self::EXAMINATION_RESULT_SECOND_DIVISION,
            "EXAMINATION_RESULT_GPA_OUT_OF_FOUR" => self::EXAMINATION_RESULT_GPA_OUT_OF_FOUR,
            "EXAMINATION_RESULT_GPA_OUT_OF_FIVE" => self::EXAMINATION_RESULT_GPA_OUT_OF_FIVE,
            "EXAMINATION_RESULT_PASSED" => self::EXAMINATION_RESULT_PASSED,
            "EXAMINATION_RESULT_PASSED_MBBS_BDS" => self::EXAMINATION_RESULT_PASSED_MBBS_BDS,
        ];


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

    public function getUserGender(): string
    {
        $userGender = '';

        $SexArray = self::getSexOptions();

        if (empty($SexArray[$this->gender])) return $userGender;

        return $SexArray[$this->gender];
    }


    /**
     * @return HasMany
     */
    public function familyMemberInfo(): HasMany
    {
        return $this->hasMany(TraineeFamilyMemberInfo::class);
    }

    /**
     * @return HasOne
     */
    public function father(): HasOne
    {
        return $this->hasOne(TraineeFamilyMemberInfo::class)->where('relation_with_trainee', TraineeFamilyMemberInfo::GUARDIAN_FATHER);
    }

    /**
     * @return HasOne
     */
    public function mother(): HasOne
    {
        return $this->hasOne(TraineeFamilyMemberInfo::class)->where('relation_with_trainee', TraineeFamilyMemberInfo::GUARDIAN_MOTHER);
    }

    /**
     * @return HasMany
     */
    public function academicQualifications(): HasMany
    {
        return $this->hasMany(TraineeAcademicQualification::class);
    }

    public function academicQualificationns(): HasMany
    {
        return $this->hasMany(TraineeAcademicQualificationn::class);
    }

    /**
     * @return HasOne
     */
    public function traineeRegistration(): HasOne
    {
        return $this->hasOne(TraineeRegistration::class);
    }


    public function traineeCourseEnroll(): HasOne
    {
        return $this->hasOne(TraineeCourseEnroll::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(TraineeBatch::class);
    }


    public const HAVE_FAMILY_OWN_HOUSE = 1;
    public const HAVE_NO_FAMILY_OWN_HOUSE = 0;
    public const HAVE_FAMILY_OWN_LAND = 1;
    public const HAVE_NO_FAMILY_OWN_LAND = 0;
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


    public function getTraineeCurrentEmploymentStatus(): string
    {
        $employmentStatus = 'Not specified';
        $employmentStatusArray = self::getTraineeCurrentEmploymentStatusOptions();
        if (empty($employmentStatusArray[$this->current_employment_status])) return $employmentStatus;

        return $employmentStatusArray[$this->current_employment_status];
    }
}
