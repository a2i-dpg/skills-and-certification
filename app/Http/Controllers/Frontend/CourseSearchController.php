<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Institute;
use App\Models\Programme;
use App\Models\Trainee;
use Illuminate\Contracts\View\View;

/**
 * Class CourseSearchController
 * @package App\Http\Controllers\Frontend
 */
class CourseSearchController extends Controller
{
    /**
     * items of trainee course search page in frontend
     *
     * @return View
     */
    const VIEW_PATH = 'frontend.search-courses.';

    public function findCourse(): View
    {
        /** @var Institute $currentInstitute */
        $currentInstitute = app('currentInstitute');
        $programmes = Programme::query();

        $maxEnrollmentNumber = [];

        if ($currentInstitute) {
            $programmes->where('institute_id', $currentInstitute->id);
        }

        $programmes = $programmes->get();

        return view(self::VIEW_PATH . 'course-list', compact('programmes', 'maxEnrollmentNumber'));
    }

    /**
     * @param int $courseId
     * @return View
     */
    public function courseDetails(int $courseId): View
    {
        $course = Course::findOrFail($courseId);
        $runningBatches = $course->runningBatches;

        return view(self::VIEW_PATH . 'course-details', ['course' => $course, 'runningBatches' => $runningBatches]);
    }

    /**
     * @param int $courseId
     * @return View
     */
    public function courseApply(int $courseId): View
    {
        /** @var Trainee $authTrainee */
        $authTrainee = Trainee::getTraineeByAuthUser();

        $course = Course::findOrFail($courseId);
        $runningBatches = $course->runningBatches;
        $academicQualifications = $authTrainee->academicQualifications->keyBy('examination');
        $guardian = $authTrainee->familyMemberInfo->keyBy('relation_with_trainee');


        return view(self::VIEW_PATH . 'course-apply', with([
            'trainee' => $authTrainee,
            'course' => $course,
            'runningBatches' => $runningBatches,
            'academicQualifications' => $academicQualifications,
            'guardian' => $guardian
        ]));
    }
}
