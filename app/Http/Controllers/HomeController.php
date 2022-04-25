<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Models\Institute;
use App\Models\IntroVideo;
use App\Models\Slider;
use App\Models\StaticPage;
use App\Models\TrainingCenter;
use App\Models\User;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeRegistration;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use App\Models\SiteSetting;
use Cache;

class HomeController extends BaseController
{
    /**
     * Show the application dashboard.
     * @return View
     */
    public function index()
    {
        //dd(config('mail.mailers.smtp.host'));
        //dd(env('MAIL_HOST'));



        /** @var Institute|null $currentInstitute */
        $currentInstitute = app('currentInstitute');
        //dd($currentInstitute);
        if($currentInstitute){
           return redirect()->route('frontend.course_search', ['instituteSlug' => $currentInstitute->slug ?? '']);
        }


        $courses = Course::query();
        $courses->active();
        $runningCourses = Course::select([
            'courses.id as id',
            'courses.institute_id as institute_id',
            'courses.title',
            'courses.course_fee',
            'courses.duration',
            'courses.cover_image',
            'courses.row_status',
            'courses.created_at',
        ]);

        $runningCourses->leftjoin('batches', 'batches.course_id','=','courses.id');
        $runningCourses->where('batches.batch_status','=','1');
        $runningCourses->where('courses.row_status','=','1');
        
        $galleries = Gallery::orderBy('id', 'DESC');
        $galleryCategories = GalleryCategory::active()
            ->orderBy('id', 'DESC');

        $sliders = Slider::active();

        $staticPage = StaticPage::orderBy('id', 'DESC')
            ->where('page_id', StaticPage::PAGE_ID_ABOUT_US);

        $trainingCenters = TrainingCenter::query();
        $traineeRegistrations = TraineeRegistration::query();
        $events = Event::query();

        $introVideo = IntroVideo::orderBy('id', 'DESC');
        /** @var User|Builder $trainers */
        $trainers = User::where('user_type_id', User::USER_TYPE_TRAINER_USER_CODE);

        if ($currentInstitute) {
            $courses->where('institute_id', $currentInstitute->id);
            
            $runningCourses->where(['courses.institute_id' => $currentInstitute->id]);
            $galleries->where(['institute_id' => $currentInstitute->id]);
            $galleryCategories->where(['institute_id' => $currentInstitute->id]);
            $sliders->where(['institute_id' => $currentInstitute->id]);
            $staticPage->where(['institute_id' => $currentInstitute->id]);
            $trainingCenters->where('institute_id', $currentInstitute->id);
            $traineeRegistrations->where('institute_id', $currentInstitute->id);
            $events->where('institute_id', $currentInstitute->id);
            $trainers->where('institute_id', $currentInstitute->id);
        }else {
            $staticPage->whereNull('institute_id');
        }

        $events->whereDate('date', '>=', Carbon::now()->format('Y-m-d'));
        $events->orderBy('date', 'ASC');

        $statistics = [
            'total_course' => $courses->count('id'),
            'total_training_center' => $trainingCenters->count('id'),
            'total_registered_trainee' => $traineeRegistrations->count('id'),
            'total_trainer' => $trainers->count('id'),
        ];

        $runningCourses = $runningCourses->get();
        $upcomingCourses = $courses->limit(8)->get();

        $galleries = $galleries->limit(8)->get();
        $galleryCategories = $galleryCategories->where(['featured' => 1])
            ->get();
        $sliders = $sliders->limit(10)
            ->get();
        $staticPage = $staticPage->limit(1)
            ->first();

        $events = $events->limit(5)->get();
        $introVideo = $introVideo->first();

        $skillServiceProviders = Institute::select(
            'institutes.id as institute_id',
            'institutes.title as institute_title',
            'institutes.logo as institute_logo',
            'institutes.address as institute_address',
            DB::raw('count(trainee_course_enrolls.id) as total_student'),
            DB::raw('count(courses.id) as total_courses')
        )
            ->leftJoin('courses', 'institutes.id', '=', 'courses.institute_id')
            ->leftJoin('trainee_course_enrolls', function (JoinClause $query) {
                $query->on('courses.id', '=', 'trainee_course_enrolls.course_id');
                $query->where(['trainee_course_enrolls.enroll_status' => TraineeCourseEnroll::ENROLL_STATUS_ACCEPT]);
            })
            ->where('institutes.row_status', Institute::ROW_STATUS_ACTIVE)
            ->groupBy(['institutes.id','institutes.title','institutes.logo','institutes.address'])
            ->get();

        return view('landing-page.welcome', compact('courses', 'upcomingCourses', 'galleries', 'sliders', 'staticPage', 'statistics', 'galleryCategories', 'events', 'introVideo', 'runningCourses', 'skillServiceProviders'));
    }

    public function success(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('frontend.trainee-enrolled-courses')
            ->with(['message' => 'আপনার পেমেন্ট সফলভাবে পরিশোধ করা হয়েছে, দয়া করে অপেক্ষা করুন', 'alert-type' => 'success']);
    }

    public function fail(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('frontend.trainee-enrolled-courses')
            ->with(['message' => 'পেমেন্ট ব্যর্থ হয়েছে, অনুগ্রহ করে পরে আবার চেষ্টা করুন', 'alert-type' => 'warning']);
    }

    public function cancel(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('frontend.trainee-enrolled-courses')
            ->with(['message' => 'পেমেন্ট বাতিল হয়েছে, দয়া করে আবার চেষ্টা করুন', 'alert-type' => 'error']);
    }

    public function sspRegistrationForm(): View
    {
        return \view('frontend.ssp.registration-form');
    }
}
