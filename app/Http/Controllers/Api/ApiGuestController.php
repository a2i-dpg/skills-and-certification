<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Course;

class ApiGuestController extends Controller
{

    public function get_coureses()
    {
        $runningCourses = Course::with('institute')->active()->select([
            'courses.id as id',
            'courses.institute_id as institute_id',
            'courses.title',
            'courses.course_fee',
            'courses.duration',
            'courses.cover_image',
            'courses.row_status',
            'courses.created_at',
        ])->limit(9);

        $runningCourses = $runningCourses->get();
        return response()->json($runningCourses,200,[],JSON_PRETTY_PRINT);
    }

}
