<?php

namespace App\Models;


use App\Traits\CreatedByUpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TraineeAcademicQualification
 * @package App\Models
 * @property int examination
 * @property int examination_name
 * @property int board
 * @property int|null institute
 * @property string roll_no
 * @property string reg_no
 * @property double result
 * @property int group
 * @property string passing_year
 * @property string|null subject
 * @property int|null course_duration
 */
class TraineeAcademicQualification extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait;

    protected $guarded = ['id'];

    public function getJSCExaminationName(): string
    {
        $examinationName = '';

        $arr = self::getJSCExaminationOptions();

        if (empty($arr[$this->examination_name])) return $examinationName;

        return $arr[$this->examination_name];

    }


    public function getSSCExaminationName(): string
    {
        $examinationName = '';

        $arr = self::getSSCExaminationOptions();

        if (empty($arr[$this->examination_name])) return $examinationName;

        return $arr[$this->examination_name];

    }

    public function getHSCExaminationName(): string
    {
        $examinationName = '';

        $arr = self::getHSCExaminationOptions();

        if (empty($arr[$this->examination_name])) return $examinationName;

        return $arr[$this->examination_name];
    }

    public function getGraduationExaminationName(): string
    {
        $examinationName = '';

        $arr = self::getGraduationExaminationOptions();

        if (empty($arr[$this->examination_name])) return $examinationName;

        return $arr[$this->examination_name];
    }

    public function getMastersExaminationName(): string
    {
        $examinationName = '';

        $arr = self::getMastersExaminationOptions();

        if (empty($arr[$this->examination_name])) return $examinationName;

        return $arr[$this->examination_name];
    }

    public function getExaminationTakingBoard(): string
    {
        $board = '';
        $arr = self::getExaminationBoardOptions();
        if (empty($arr[$this->board])) return $board;

        return $arr[$this->board];
    }

    public function getExaminationGroup(): string
    {
        $group = '';
        $arr = self::getExaminationGroupOptions();
        if (empty($arr[$this->group])) return $group;

        return $arr[$this->group];
    }

    public function getExaminationResult(): string
    {
        $examinationResultArray = self::getExaminationResultOptions();

        $exam_result = '';
        if (empty($examinationResultArray[$this->result])) return $exam_result;
        return $examinationResultArray[$this->result];
    }

    public const EXAMINATION_SSC = '1';
    public const EXAMINATION_HSC = '2';
    public const EXAMINATION_GRADUATION = '3';
    public const EXAMINATION_MASTERS = '4';
    public const EXAMINATION_JSC = '5';

    public static function getExaminationOptions(): array
    {
        return [
            self::EXAMINATION_JSC => __('J.S.C'),
            self::EXAMINATION_SSC => __('S.S.C'),
            self::EXAMINATION_HSC => __('H.S.C'),
            self::EXAMINATION_GRADUATION => __('Graduation'),
            self::EXAMINATION_MASTERS => __('Master\'s'),
        ];
    }

    public function getExamination(): string
    {
        $examination = '';
        $examinationArray = self::getExaminationOptions();

        if (empty($examinationArray[$this->examination])) return $examination;

        return $examinationArray[$this->examination];

    }

    public const SSC_EXAMINATION_SSC = 1;
    public const SSC_EXAMINATION_DAKHIL = 2;
    public const SSC_EXAMINATION_SSC_OR_DAKHIL_VOCATIONAL = 3;
    public const SSC_EXAMINATION_O_LEVEL = 4;
    public const SSC_EXAMINATION_SSC_EQUIVALENT = 5;

    public static function getSSCExaminationOptions(): array
    {
        return [
            self::SSC_EXAMINATION_SSC => __('SSC'),
            self::SSC_EXAMINATION_DAKHIL => __('Dakhil'),
            self::SSC_EXAMINATION_SSC_OR_DAKHIL_VOCATIONAL => __('SSC/Dakhil Vocational'),
            self::SSC_EXAMINATION_O_LEVEL => __('O level Cambridge'),
            self::SSC_EXAMINATION_SSC_EQUIVALENT => __('SSC Equivalent'),
        ];
    }

    public const JSC_EXAMINATION_JSC = 1;
    public const JDC_EXAMINATION_JDC = 2;

    public static function getJSCExaminationOptions(): array
    {
        return [
            self::JSC_EXAMINATION_JSC => __('JSC'),
            self::JDC_EXAMINATION_JDC => __('JDC'),
        ];
    }

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

    public static function getExaminationBoardOptions(): array
    {
        return [
            self::EXAMINATION_BOARD_DHAKA => __('Dhaka'),
            self::EXAMINATION_BOARD_COMILLA => __('Comilla'),
            self::EXAMINATION_BOARD_RAJSHAHI => __('Rajshahi'),
            self::EXAMINATION_BOARD_JESSOR => __('Jessore'),
            self::EXAMINATION_BOARD_CHITTAGONG => __('Chittagong'),
            self::EXAMINATION_BOARD_BARISAL => __('Barisal'),
            self::EXAMINATION_BOARD_SYLHET => __('Syhlet'),
            self::EXAMINATION_BOARD_DINAJPUR => __('Dinajpur'),
            self::EXAMINATION_BOARD_MADRASAH => __('Madrasah'),
            self::EXAMINATION_BOARD_TECHNICAL => __('Technical'),
            self::EXAMINATION_BOARD_EDEXCEL_INTERNATIONAL => __('Cambridge International - IGCE'),
            self::EXAMINATION_BOARD_BANGLADESH_NURSING_AND_MIDWIFERY_COUNCIL => __('Nursing and Midwifery Council'),
            self::EXAMINATION_BOARD_OTHERS => __('Others'),
        ];
    }

    public const EXAMINATION_GROUP_SCIENCE = 1;
    public const EXAMINATION_GROUP_HUMANITIES = 2;
    public const EXAMINATION_GROUP_BUSINESS = 3;
    public const EXAMINATION_GROUP_OTHERS = 4;

    public static function getExaminationGroupOptions(): array
    {
        return
            [
                self::EXAMINATION_GROUP_SCIENCE => __('Science'),
                self::EXAMINATION_GROUP_HUMANITIES => __('Humanities'),
                self::EXAMINATION_GROUP_BUSINESS => __('Business'),
                self::EXAMINATION_GROUP_OTHERS => __('Others'),
            ];
    }

    public const EXAMINATION_RESULT_FIRST_DIVISION = 6;
    public const EXAMINATION_RESULT_SECOND_DIVISION = 7;
    public const EXAMINATION_RESULT_GPA_OUT_OF_FOUR = 3;
    public const EXAMINATION_RESULT_GPA_OUT_OF_FIVE = 4;
    public const EXAMINATION_RESULT_PASSED = 8;
    public const EXAMINATION_RESULT_PASSED_MBBS_BDS = 9;

    public static function getExaminationResultOptions(): array
    {
        return
            [
                self::EXAMINATION_RESULT_FIRST_DIVISION => __('First Division'),
                self::EXAMINATION_RESULT_SECOND_DIVISION => __('Second Division'),
                self::EXAMINATION_RESULT_GPA_OUT_OF_FOUR => __('out of 4.00'),
                self::EXAMINATION_RESULT_GPA_OUT_OF_FIVE => __('out of 5.00'),
                self::EXAMINATION_RESULT_PASSED => __('Passed'),
                self::EXAMINATION_RESULT_PASSED_MBBS_BDS => __('Passed M.B.B.S/B.D.S'),
            ];
    }


    public const HSC_EXAMINATION_HSC = 1;
    public const HSC_EXAMINATION_ALIM = 2;
    public const HSC_EXAMINATION_DIPLOMA_IN_COMMERCE = 3;
    public const HSC_EXAMINATION_DIPLOMA_IN_ENGINEERING = 4;
    public const HSC_EXAMINATION_A_LEVEL = 5;
    public const HSC_EXAMINATION_HSC_EQUIVALENT = 6;
    public const HSC_EXAMINATION_DIPLOMA_IN_NURSING_OR_MIDWIFERY = 7;
    public const HSC_EXAMINATION_HSC_VOCATIONAL = 8;

    public static function getHSCExaminationOptions(): array
    {
        return
            [
                self::HSC_EXAMINATION_HSC => __('H.S.C'),
                self::HSC_EXAMINATION_ALIM => __('Alim'),
                self::HSC_EXAMINATION_DIPLOMA_IN_COMMERCE => __('Diploma in commerce/BM'),
                self::HSC_EXAMINATION_DIPLOMA_IN_ENGINEERING => __('Diploma in Engineering/Diploma'),
                self::HSC_EXAMINATION_A_LEVEL => __('A Level/Sr.Cambridge'),
                self::HSC_EXAMINATION_HSC_EQUIVALENT => __('H.S.C Equivalent'),
                self::HSC_EXAMINATION_DIPLOMA_IN_NURSING_OR_MIDWIFERY => __('Diploma in Nursing/Midwifery'),
                self::HSC_EXAMINATION_HSC_VOCATIONAL => __('H.S.C Vocational'),
            ];
    }

    public const GRADUATION_EXAMINATION_BSC_ENGINEERING = 1;
    public const GRADUATION_EXAMINATION_BSC_AGRICULTURE = 2;
    public const GRADUATION_EXAMINATION_MBBS_BDS = 3;
    public const GRADUATION_EXAMINATION_HONOURS = 4;
    public const GRADUATION_EXAMINATION_PASS_COURSE = 5;
    public const GRADUATION_EXAMINATION_AMIE = 6;
    public const GRADUATION_EXAMINATION_BSC_NURSING = 7;
    public const GRADUATION_EXAMINATION_OTHERS = 8;

    public static function getGraduationExaminationOptions(): array
    {
        return
            [
                self::GRADUATION_EXAMINATION_BSC_ENGINEERING => __('B.Sc Engineering/Architecture'),
                self::GRADUATION_EXAMINATION_BSC_AGRICULTURE => __('B.Sc Agriculture Science'),
                self::GRADUATION_EXAMINATION_MBBS_BDS => __('M.B.B.S/B.D.S'),
                self::GRADUATION_EXAMINATION_HONOURS => __('Honours'),
                self::GRADUATION_EXAMINATION_PASS_COURSE => __('Pass Course'),
                self::GRADUATION_EXAMINATION_AMIE => __('A & B Section of A.M.I.E'),
                self::GRADUATION_EXAMINATION_BSC_NURSING => __('B.Sc/Bachelor Nursing'),
                self::GRADUATION_EXAMINATION_OTHERS => __('Others'),
            ];
    }

    public const MASTERS_EXAMINATION_MSC_ENGINEERING = 1;
    public const MASTERS_EXAMINATION_MSC_AGRICULTURE = 2;
    public const MASTERS_EXAMINATION_MS_MEDICAL = 3;
    public const MASTERS_EXAMINATION_MASTERS = 4;
    public const MASTERS_EXAMINATION_MSC_NURSING = 5;
    public const MASTERS_EXAMINATION_OTHERS = 6;

    public static function getMastersExaminationOptions(): array
    {
        return
            [
                self::MASTERS_EXAMINATION_MSC_ENGINEERING => __('M.Sc Engineering/Architecture'),
                self::MASTERS_EXAMINATION_MSC_AGRICULTURE => __('M.Sc Agriculture Science'),
                self::MASTERS_EXAMINATION_MS_MEDICAL => __('M.D/M.S/M.Sc(Medical)'),
                self::MASTERS_EXAMINATION_MASTERS => __('Masters'),
                self::MASTERS_EXAMINATION_MSC_NURSING => __('M.Sc Nursing'),
                self::MASTERS_EXAMINATION_OTHERS => __('Others'),
            ];
    }


    public const EXAMINATION_COURSE_DURATION_ONE_YEAR = 1;
    public const EXAMINATION_COURSE_DURATION_TWO_YEAR = 2;
    public const EXAMINATION_COURSE_DURATION_THREE_YEAR = 3;
    public const EXAMINATION_COURSE_DURATION_FOUR_YEAR = 4;
    public const EXAMINATION_COURSE_DURATION_FIVE_YEAR = 5;

    public static function getExaminationCourseDurationOptions(): array
    {
        return
            [
                self::EXAMINATION_COURSE_DURATION_ONE_YEAR => __('01 Year'),
                self::EXAMINATION_COURSE_DURATION_TWO_YEAR => __('02 Year'),
                self::EXAMINATION_COURSE_DURATION_THREE_YEAR => __('03 Year'),
                self::EXAMINATION_COURSE_DURATION_FOUR_YEAR => __('04 Year'),
                self::EXAMINATION_COURSE_DURATION_FIVE_YEAR => __('05 Year'),
            ];
    }

    public const EXAMINATION_UNIVERSITY_UNIVERSITY_OF_RAJSHAHI = 1;
    public const EXAMINATION_UNIVERSITY_UNIVERSITY_OF_DHAKA = 2;
    public const EXAMINATION_UNIVERSITY_UNIVERSITY_OF_CHITTAGONG = 3;
    public const EXAMINATION_UNIVERSITY_UNIVERSITY_OF_KHULNA = 4;

    public static function getUniversities(): array
    {
        return [
            self::EXAMINATION_UNIVERSITY_UNIVERSITY_OF_RAJSHAHI => __('University of Rajshahi'),
            self::EXAMINATION_UNIVERSITY_UNIVERSITY_OF_DHAKA => __('University of Dhaka'),
            self::EXAMINATION_UNIVERSITY_UNIVERSITY_OF_CHITTAGONG => __('University of Chittagong'),
            self::EXAMINATION_UNIVERSITY_UNIVERSITY_OF_KHULNA => __('University of Khulna'),
        ];
    }

    public function getCurrentUniversity(): ?string
    {
        $institute = '';
        $arr = self::getUniversities();
        if (empty($arr[$this->institute])) return $institute;

        return $arr[$this->institute];
    }

    public function trainee(): BelongsTo
    {
        return $this->BelongsTo(Trainee::class);
    }
}
