<?php

use Illuminate\Support\Facades\Route;

Route::get('/success', [\App\Http\Controllers\HomeController::class, 'success'])->name('success');
Route::get('/fail', [\App\Http\Controllers\HomeController::class, 'fail'])->name('fail');
Route::get('/cancel', [\App\Http\Controllers\HomeController::class, 'cancel'])->name('cancel');

Route::get('/email/verify', [\App\Http\Controllers\Admin\Auth\VerificationController::class, 'show'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Admin\Auth\VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend',[\App\Http\Controllers\Admin\Auth\VerificationController::class, 'resend'] )->name('verification.resend');

Route::get('/registration/verify', [\App\Http\Controllers\Frontend\TraineeRegistrationController::class, 'showVerificationNotice'])->name('registration-verification.notice');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/check-unique-user-email', [App\Http\Controllers\Admin\UserController::class, 'checkUserEmailUniqueness'])->name('users.check-unique-user-email');
});

Route::post('ipn-handler', [App\Http\Controllers\Frontend\TraineeController::class, 'ipnHandler'])->name('api-ipn-handler');
Route::post('trainee-bulk-import', [App\Http\Controllers\TraineeManagementController::class, 'importTrainee'])->name('api-trainee-bulk-import');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::resources([
        'institutes' => App\Http\Controllers\InstituteController::class,
        'courses' => App\Http\Controllers\CourseController::class,
        'branches' => App\Http\Controllers\BranchController::class,
        'training-centers' => App\Http\Controllers\TrainingCenterController::class,
        'programmes' => App\Http\Controllers\ProgrammeController::class,
        'batches' => App\Http\Controllers\BatchController::class,
        'galleries' => App\Http\Controllers\GalleryController::class,
        'gallery-categories' => App\Http\Controllers\GalleryCategoryController::class,
        'certificate-templates' => App\Http\Controllers\CertificateTemplateController::class,
        'sliders' => App\Http\Controllers\SliderController::class,
        'static-page' => App\Http\Controllers\StaticPageController::class,
        'videos' => App\Http\Controllers\VideoController::class,
        'video-categories' => App\Http\Controllers\VideoCategoryController::class,
        'events' => App\Http\Controllers\EventController::class,
        'intro-videos' => App\Http\Controllers\IntroVideoController::class,
        'question-answers' => App\Http\Controllers\QuestionAnswerController::class,
        'examination-types' => App\Http\Controllers\ExaminationTypeController::class,
        'examinations' => App\Http\Controllers\ExaminationController::class,
        'examination-results' => App\Http\Controllers\ExaminationResultController::class,
        'routines' => App\Http\Controllers\RoutineController::class,
        'examination-routines' => App\Http\Controllers\ExaminationRoutineController::class,
        'headers' => App\Http\Controllers\HeaderController::class,
        'site-setting' => App\Http\Controllers\SiteSettingController::class,
    ]);

    Route::get('trainee-feedback/{id}/index', [App\Http\Controllers\TraineeFeedbackController::class, 'index'])->name('trainer-feedbacks.index');
    Route::post('trainee-feedback/store', [App\Http\Controllers\TraineeFeedbackController::class, 'store'])->name('trainer-feedbacks.store');

    Route::get('daily-routine', [App\Http\Controllers\RoutineController::class, 'dailyRoutine'])->name('daily-routine');

    Route::get('examination-routines-all', [App\Http\Controllers\ExaminationRoutineController::class, 'examinationRoutine'])->name('examination-routine');
    Route::post('examination-routine/filter', [App\Http\Controllers\ExaminationRoutineController::class, 'examinationRoutineFilter'])->name('examination-routine.filter');
    Route::get('examination-result-batch/{id}', [App\Http\Controllers\ExaminationResultController::class, 'batchResult'])->name('examination-result.batch');
    Route::get('examination-result-batch-add/{id}', [App\Http\Controllers\ExaminationResultController::class, 'batchResultadd'])->name('examination-result.batch-add');
    Route::get('examination-result-batch-edit/{id}', [App\Http\Controllers\ExaminationResultController::class, 'batchResultEdit'])->name('examination-result.batch.edit');
    Route::put('examination-result-batch-update', [App\Http\Controllers\ExaminationResultController::class, 'batchResultUpdate'])->name('examination-result.batch.update');
    Route::post('examination-result-batch', [App\Http\Controllers\ExaminationResultController::class, 'batchResultStore'])->name('examination-result.batch.store');
    Route::put('trainee-course-enroll-accept/{trainee_course_enroll_id}', [App\Http\Controllers\Frontend\TraineeRegistrationController::class, 'acceptTraineeCourseEnroll'])->name('trainee-course-enroll-accept');
    Route::put('trainee-course-enroll-reject/{trainee_course_enroll_id}', [App\Http\Controllers\Frontend\TraineeRegistrationController::class, 'rejectTraineeCourseEnroll'])->name('trainee-course-enroll-reject');
    Route::put('trainee-accept-all', [App\Http\Controllers\TraineeController::class, 'traineeAcceptNowAll'])->name('trainee-accept-now-all');
    Route::put('trainee-reject-all', [App\Http\Controllers\TraineeController::class, 'traineeRejectNowAll'])->name('trainee-reject-now-all');

    Route::post('static-page/image-upload', [App\Http\Controllers\StaticPageController::class, 'imageUpload'])->name('staticPage.imageUpload');
    Route::post('institutes/datatable', [App\Http\Controllers\InstituteController::class, 'getDatatable'])->name('institutes.datatable');
    Route::post('branches/datatable', [App\Http\Controllers\BranchController::class, 'getDatatable'])->name('branches.datatable');
    Route::post('training-centers/datatable', [App\Http\Controllers\TrainingCenterController::class, 'getDatatable'])->name('training-centers.datatable');
    Route::post('courses/datatable', [App\Http\Controllers\CourseController::class, 'getDatatable'])->name('courses.datatable');
    Route::post('programmes/datatable', [App\Http\Controllers\ProgrammeController::class, 'getDatatable'])->name('programmes.datatable');
    Route::post('batches/datatable', [App\Http\Controllers\BatchController::class, 'getDatatable'])->name('batches.datatable');
    Route::post('gallery/datatable', [App\Http\Controllers\GalleryController::class, 'getDatatable'])->name('gallery.datatable');
    Route::post('gallery-categories/datatable', [App\Http\Controllers\GalleryCategoryController::class, 'getDatatable'])->name('gallery-categories.datatable');
    Route::post('certificate-templates/datatable', [App\Http\Controllers\CertificateTemplateController::class, 'getDatatable'])->name('certificate-templates.datatable');
    Route::post('sliders/datatable', [App\Http\Controllers\SliderController::class, 'getDatatable'])->name('sliders.datatable');
    Route::post('static-page/datatable', [App\Http\Controllers\StaticPageController::class, 'getDatatable'])->name('static-page.datatable');
    Route::post('videos/datatable', [App\Http\Controllers\VideoController::class, 'getDatatable'])->name('videos.datatable');
    Route::post('video-categories/datatable', [App\Http\Controllers\VideoCategoryController::class, 'getDatatable'])->name('video-categories.datatable');
    Route::post('events/datatable', [App\Http\Controllers\EventController::class, 'getDatatable'])->name('events.datatable');
    Route::post('intro-videos/datatable', [App\Http\Controllers\IntroVideoController::class, 'getDatatable'])->name('intro-videos.datatable');
    Route::post('question-answers/datatable', [App\Http\Controllers\QuestionAnswerController::class, 'getDatatable'])->name('question-answers.datatable');

    Route::get('batches/{id}/trainees', [App\Http\Controllers\TraineeBatchController::class, 'index'])->name('batches.trainees');
    Route::get('batches/{id}/trainer-mapping', [App\Http\Controllers\BatchController::class, 'trainerMapping'])->name('batches.trainer-mapping');
    Route::post('batches/trainer-mapping-update', [App\Http\Controllers\BatchController::class, 'trainerMappingUpdate'])->name('batches.trainer-mapping-update');
    Route::post('batches/{id}/trainees-import', [App\Http\Controllers\TraineeBatchController::class, 'importTrainee'])->name('batches.trainees-import');

    Route::post('batches/{id}/trainees/datatable', [App\Http\Controllers\TraineeBatchController::class, 'getDatatable'])->name('batches.trainees.datatable');
    Route::post('batches/certificates/datatable', [App\Http\Controllers\TraineeCertificateController::class, 'getBatchCertificateDatatable'])->name('batches.certificates.datatable');
    Route::get('batches/certificates/edit/{batch_id}', [App\Http\Controllers\TraineeCertificateController::class, 'certificateEdit'])->name('batches.certificates.edit');
    Route::get('batches/certificates/index', [App\Http\Controllers\TraineeCertificateController::class, 'index'])->name('batches.certificates.index');
    Route::get('batches/{id}/certificates/create', [App\Http\Controllers\TraineeCertificateController::class, 'create'])->name('batch.certificates.create');
    Route::post('batches/{id}/certificates/store', [App\Http\Controllers\TraineeCertificateController::class, 'store'])->name('batch.certificates.store');

    Route::get('trainee/certificates/request', [App\Http\Controllers\TraineeCertificateController::class, 'certificateRequestIndex'])->name('trainee.certificates.request');
    Route::get('trainee/certificates/request/accepted/list', [App\Http\Controllers\TraineeCertificateController::class, 'certificateRequestAcceptedIndex'])->name('trainee.certificates.request.accepted');
    Route::post('trainee/certificates/request/accepted/list/datatable', [App\Http\Controllers\TraineeCertificateController::class, 'certificateRequestAcceptedDatatable'])->name('trainee.certificates.request.accepted.datatable');
    Route::post('trainee/certificates/request/datatable', [App\Http\Controllers\TraineeCertificateController::class, 'certificateRequestDatatable'])->name('trainee.certificates.request.datatable');
    Route::get('trainee/certificates/request/view/{id}', [App\Http\Controllers\TraineeCertificateController::class, 'certificateRequestView'])->name('trainee.certificates.request.view');
    Route::post('trainee/certificates/request/store/{id}', [App\Http\Controllers\TraineeCertificateController::class, 'certificateRequestReview'])->name('trainee.certificates.request.store');

    Route::get('trainee-registrations', [App\Http\Controllers\TraineeRegistrationManagementController::class, 'index'])
        ->name('trainee.registrations.index');
    Route::get('trainee-preferred_batch/{id}', [App\Http\Controllers\TraineeRegistrationManagementController::class, 'preferredBatch'])
        ->name('trainee.preferred.batch');
    Route::post('trainee-confirm_batch', [App\Http\Controllers\TraineeRegistrationManagementController::class, 'assignToBatch'])
        ->name('trainee.batch.assign');

    Route::put('trainee-course-batch-select/{trainee_course_enroll_id}',
        [App\Http\Controllers\TraineeRegistrationManagementController::class, 'acceptTraineeCourseEnroll'])
        ->name('trainee-course-batch-select');
    Route::post('trainee-registrations/datatable', [App\Http\Controllers\TraineeRegistrationManagementController::class, 'getDatatable'])
        ->name('trainee.registrations.datatable');

    Route::resource('batches', App\Http\Controllers\BatchController::class);

    Route::get('batch-on-going/{batch}', [App\Http\Controllers\BatchController::class, 'batchOnGoing'])
        ->name('batch-on-going');
    Route::get('batch-complete/{batch}', [App\Http\Controllers\BatchController::class, 'batchComplete'])
        ->name('batch-complete');

    Route::post('programmes/check-programme-code', [App\Http\Controllers\ProgrammeController::class, 'checkCode'])->name('check-programme-code');
    Route::post('institutes/check-institute-code', [App\Http\Controllers\InstituteController::class, 'checkCode'])->name('check-institute-code');
    Route::post('static-page/check-page-id', [App\Http\Controllers\StaticPageController::class, 'checkCode'])->name('check-page-id');
    Route::post('courses/check-course-code', [App\Http\Controllers\CourseController::class, 'checkCode'])->name('check-course-code');
    Route::post('batches/check-batch-code', [App\Http\Controllers\BatchController::class, 'checkCode'])->name('check-batch-code');
//    Route::post('batches/batch-training-centers', [App\Http\Controllers\BatchController::class, 'batchTrainingCenter'])->name('batch-training-centers');

    Route::resource('visitor-feedback', App\Http\Controllers\Frontend\VisitorFeedbackController::class)->only(['index', 'destroy', 'show']);
    Route::post('visitor-feedback/datatable', [App\Http\Controllers\Frontend\VisitorFeedbackController::class, 'getDatatable'])->name('visitor-feedback.datatable');
    Route::post('featured-galleries', [\App\Http\Controllers\GalleryCategoryController::class, 'updateFeaturedGalleries'])->name('gallery-album.change-featured');

    Route::get('trainees', [App\Http\Controllers\TraineeManagementController::class, 'index'])
        ->name('trainees.index');
    Route::post('trainees/datatable', [App\Http\Controllers\TraineeManagementController::class, 'getDatatable'])
        ->name('trainees.datatable');
    Route::post('trainees/add-to-organization', [App\Http\Controllers\TraineeManagementController::class, 'addTraineeToOrganization'])
        ->name('trainees.add-to-organization');

    Route::post('trainees/trainee-assigned-organizations', [App\Http\Controllers\TraineeManagementController::class, 'getTraineeAssignedOrganizations'])
        ->name('trainees.trainee-assigned-organization');


    Route::get('trainee-accept-list', [App\Http\Controllers\TraineeController::class, 'traineeAcceptList'])
        ->name('trainee.accept-list');
    Route::post('trainee-accept-list/datatable', [App\Http\Controllers\TraineeController::class, 'getAcceptDatatable'])
        ->name('trainee.acceptList.datatable');

    Route::post('trainee-accept-list/add-to-batch', [App\Http\Controllers\TraineeRegistrationManagementController::class, 'addTraineeToBatch'])
        ->name('trainee.add-to-batch');

    Route::put('trainee-accept-list/{trainee}', [App\Http\Controllers\TraineeRegistrationManagementController::class, 'addSingleTraineeToBatch'])
        ->name('trainee.add-single-trainee-to-batch');


    Route::get('trainees/certificate/course/{trainee_course_enroll}', [App\Http\Controllers\TraineeManagementController::class, 'traineeCertificateCourseWise'])
        ->name('trainees.certificate');

    Route::get('trainees/certificate/{trainee}', [App\Http\Controllers\TraineeManagementController::class, 'traineeCertificateList'])
        ->name('trainees.certificate.course');

    Route::post('examination-types/datatable', [App\Http\Controllers\ExaminationTypeController::class, 'getDatatable'])->name('examination-types.datatable');
    Route::post('examinations/datatable', [App\Http\Controllers\ExaminationController::class, 'getDatatable'])->name('examinations.datatable');
    Route::post('examinations/status', [App\Http\Controllers\ExaminationController::class, 'examinationStatus'])->name('examinations.status');
    Route::post('examinations/code_check', [App\Http\Controllers\ExaminationController::class, 'examinationCodeCheck'])->name('examinations.examinationCodeCheck');
    Route::post('examination-results/datatable', [App\Http\Controllers\ExaminationResultController::class, 'getDatatable'])->name('examination-results.datatable');
    Route::get('examination-results/get-trainees/{examination_id?}', [App\Http\Controllers\ExaminationResultController::class, 'getTrainees'])->name('examination-results.get-trainees');
    Route::post('routines/datatable', [App\Http\Controllers\RoutineController::class, 'getDatatable'])->name('routines.datatable');
    Route::post('examination-routines/datatable', [App\Http\Controllers\ExaminationRoutineController::class, 'getDatatable'])->name('examination-routines.datatable');

    Route::post('routine-events', [\App\Http\Controllers\RoutineController::class, 'allRoutineEvents'])->name('routine.events');
    Route::post('specific-date-routines', [\App\Http\Controllers\RoutineController::class, 'getRoutine'])->name('specific-date-routines');
    Route::post('completed-batches-datatable', [\App\Http\Controllers\TraineeResultManagementController::class, 'completedBatchesDatatable'])->name('completed-batches.datatable');
    Route::get('completed-batches', [\App\Http\Controllers\TraineeResultManagementController::class, 'showCompletedBatches'])->name('completed-batches');
    Route::post('trainee-final-result-datatable', [\App\Http\Controllers\TraineeResultManagementController::class, 'traineeResultDatatable'])->name('trainee-final-result.datatable');
    Route::get('trainee-final-result-list/{id}', [\App\Http\Controllers\TraineeResultManagementController::class, 'showTraineeResultList'])->name('show.trainee.final-result-list');

    Route::get('trainee-feedbacks', [\App\Http\Controllers\Frontend\VisitorFeedbackController::class, 'showTraineeFeedbacks'])->name('show.trainee-feedbacks');
    Route::post('trainee-feedbacks-datatable', [\App\Http\Controllers\Frontend\VisitorFeedbackController::class, 'traineeFeedbackDatatable'])->name('trainee-feedbacks.datatable');
    Route::get('trainer-feedbacks', [\App\Http\Controllers\Frontend\VisitorFeedbackController::class, 'showTrainerFeedbacks'])->name('show.trainer-feedbacks');
    Route::post('trainer-feedbacks-datatable', [\App\Http\Controllers\Frontend\VisitorFeedbackController::class, 'trainerFeedbackDatatable'])->name('trainer-feedbacks.datatable');

    Route::post('headers-datatable', [\App\Http\Controllers\HeaderController::class, 'getDatatable'])->name('headers.datatable');
});

Route::group(['as' => 'frontend.'], function () {
    Route::group(['middleware' => ['authTrainee', 'verified']], function () {
        Route::get('trainee-profile', [App\Http\Controllers\Frontend\TraineeController::class, 'index'])->name('trainee');
        Route::get('edit-personal-info', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'editPersonalInfo'])->name('edit-personal-info');
        Route::get('add-guardian-info/{id?}', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'editGuardianInfo'])->name('add-guardian-info');
        Route::put('edit-personal-info/{id}', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'updatePersonalInfo'])->name('update-personal-info');
        Route::get('add-edit-education/{id}', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'addEditEducation'])->name('add-edit-education');
        Route::post('store-education-info', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'storeEducationInfo'])->name('trainee-education-info.store');
        Route::post('store-guardian-info', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'storeGuardianInfo'])->name('guardian-info.store');
        Route::put('update-guardian-info/{id}', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'updateGuardianInfo'])->name('guardian-info.update');
        Route::get('education-info-single-delete/{id?}/{trainee_id?}', [App\Http\Controllers\Frontend\TraineeProfileController::class, 'educationInfoSingleDelete'])->name('trainee-education-info.single-delete');

        Route::get('trainee-enrolled-courses', [App\Http\Controllers\Frontend\TraineeController::class, 'traineeEnrolledCourses'])->name('trainee-enrolled-courses');
        Route::post('trainee-enrolled-courses/datatable', [App\Http\Controllers\Frontend\TraineeController::class, 'traineeCourseGetDatatable'])->name('trainee-courses-datatable');
        Route::get('trainee/{id}/batch-trainers', [App\Http\Controllers\Frontend\TrainerFeedbackController::class, 'index'])->name('trainee-batch-trainer');
        Route::get('trainee/{id}/feedback', [App\Http\Controllers\Frontend\TrainerFeedbackController::class, 'feedback'])->name('trainee-feedback');
        Route::post('trainee/batch/feedback', [App\Http\Controllers\Frontend\TrainerFeedbackController::class, 'store'])->name('trainee-feedback-store');
        Route::get('trainee-certificate-view/{trainee_course_enroll}', [App\Http\Controllers\Frontend\TraineeController::class, 'traineeCertificateView'])->name('trainee-certificate-view');
        Route::post('trainee-profile/trainee-course-enroll-pay-now/{trainee_course_enroll_id}', [App\Http\Controllers\Frontend\TraineeController::class, 'traineeCourseEnrollPayNow'])->name('trainee-course-enroll-pay-now');
        Route::get('trainee-current-organization', [App\Http\Controllers\Frontend\TraineeController::class, 'traineeCurrentOrganization'])->name('trainee-current-organization');
        Route::post('course-enroll', [App\Http\Controllers\Frontend\TraineeCourseEnrollmentController::class, 'courseEnroll'])->name('course-enroll');
        Route::get('course-enroll-success/{courseId}', [App\Http\Controllers\Frontend\TraineeCourseEnrollmentController::class, 'showCourseEnrollSuccessPage'])->name('show.course-enroll-success');

        Route::get('class-routine/{course_enroll_id}', [App\Http\Controllers\Frontend\RoutineController::class, 'index'])->name('class-routine');
        Route::post('class-routine-datatable/{course_enroll_id}', [App\Http\Controllers\Frontend\RoutineController::class, 'getTraineeRoutine'])->name('class-routine-datatable');
        Route::post('exam-routine-datatablee/{course_enroll_id}', [App\Http\Controllers\Frontend\RoutineController::class, 'getTraineeExamRoutine'])->name('exam-routine-datatable');
    });

    Route::get('ssp-registration', [\App\Http\Controllers\HomeController::class, 'sspRegistrationForm'])->name('ssp-registration');
    Route::post('ssp-registration', [\App\Http\Controllers\InstituteController::class, 'SSPRegistration'])->name('ssp-registration');

    Route::get('trainee-registration/success/{accessKey}', [App\Http\Controllers\Frontend\TraineeRegistrationController::class, 'registrationSuccess'])->name('trainee-registration.success');
    Route::get('trainee-login-callback', [App\Http\Controllers\Frontend\TraineeLoginController::class, 'ssoLoginCallback'])->name('trainee.sso-login-callback');
//    Route::get('trainee-password-reset', [App\Http\Controllers\Frontend\TraineeLoginController::class, 'passwordResetForm'])->name('trainee.password-reset');
    Route::post('trainee-logout', [App\Http\Controllers\Frontend\TraineeLoginController::class, 'logout'])->name('trainee.logout-submit');
    Route::get('keycloak-trainee-login', [App\Http\Controllers\Frontend\TraineeLoginController::class, 'loginWithKeycloak'])->name('trainee.keycloak-login-form');

    Route::get('trainee-certificate', [App\Http\Controllers\Frontend\TraineeController::class, 'certificate'])->name('certificate');
    Route::get('trainee-certificate/download', [App\Http\Controllers\Frontend\TraineeController::class, 'certificateDownload'])->name('certificate.download');
    Route::get('trainee-certificate-two', [App\Http\Controllers\Frontend\TraineeController::class, 'certificateTwo'])->name('certificate-two');

    Route::resources([
        'trainee-registrations' => App\Http\Controllers\Frontend\TraineeRegistrationController::class,
    ]);

    Route::post('trainee/recover-access-key-by-email', [\App\Http\Controllers\Frontend\TraineeController::class, 'sendMailToRecoverAccessKey'])->name('trainee.recover-access-key');
    Route::post('trainee/registration-success-email', [\App\Http\Controllers\Frontend\TraineeController::class, 'sendMailToRegistrationSuccess'])->name('trainee.registration-success-mail');
    Route::get('trainee/check-unique-email', [\App\Http\Controllers\Frontend\TraineeController::class, 'checkTraineeEmailUniqueness'])->name('trainee.check-unique-email');
    Route::get('trainee/check-unique-nid', [\App\Http\Controllers\Frontend\TraineeController::class, 'checkTraineeUniqueNID'])->name('trainee.check-unique-nid');
    Route::get('trainee/check-unique-birth_certificate_no', [\App\Http\Controllers\Frontend\TraineeController::class, 'checkTraineeUniqueBirthCertificateNo'])->name('trainee.check-unique-birth_certificate_no');
    Route::get('trainee/check-unique-passport-no', [\App\Http\Controllers\Frontend\TraineeController::class, 'checkTraineeUniquePassportId'])->name('trainee.check-unique-passport-no');

    Route::get('/ssp-list', [App\Http\Controllers\Frontend\InstitutePageController::class, 'index'])->name('institute-list');
    Route::get('ssp-details/{id}', [App\Http\Controllers\Frontend\InstitutePageController::class, 'details'])->name('institute-details');
});
Route::get('certificate/{traineeCourseEnrollId}', [App\Http\Controllers\CertificateGenerateController::class, 'generatePDF'])->name('certificate.generation');
Route::get('trainee-certificate-request/{batch_id}', [App\Http\Controllers\Frontend\CertificateController::class, 'index'])->name('certificate-request');
Route::post('trainee-certificate-request', [App\Http\Controllers\Frontend\CertificateController::class, 'store'])->name('certificate-request.store');

$routesWithoutInstituteSlug = function () {
    Route::post('institute-events-date', [App\Http\Controllers\Frontend\EventPageController::class, 'instituteEventDate'])->name('institute-events-date');
    Route::post('institute-events', [App\Http\Controllers\Frontend\EventPageController::class, 'instituteEvent'])->name('institute-events');
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('main');
    Route::get('courses-search', [App\Http\Controllers\Frontend\CourseSearchController::class, 'findCourse'])->name('course_search');
    Route::get('course-details/{course_id}', [App\Http\Controllers\Frontend\CourseSearchController::class, 'courseDetails'])->name('course-details');
    Route::get('course-apply/{course_id}', [App\Http\Controllers\Frontend\CourseSearchController::class, 'courseApply'])->name('course-apply');

    Route::get('skill-videos', [App\Http\Controllers\Frontend\TraineeController::class, 'videos'])->name('skill_videos');
    Route::get('skill-videos/{video_id}', [App\Http\Controllers\Frontend\TraineeController::class, 'singleVideo'])->name('skill-single-video');
    Route::get('advice-page', [App\Http\Controllers\Frontend\TraineeController::class, 'advicePage'])->name('advice-page');
    Route::get('general-ask-page', [App\Http\Controllers\Frontend\TraineeController::class, 'generalAskPage'])->name('general-ask-page');
    Route::get('contact-us-page', [App\Http\Controllers\Frontend\TraineeController::class, 'contactUsPage'])->name('contact-us-page');
    Route::post('visitor-feedback-store', [App\Http\Controllers\Frontend\VisitorFeedbackController::class, 'store'])->name('visitor-feedback.store');
    Route::get('yearly-training-calendar', [App\Http\Controllers\Frontend\YearlyTrainingCalendarController::class, 'index'])->name('yearly-training-calendar.index');
    Route::post('yearly-training-calendar-ajax', [App\Http\Controllers\Frontend\YearlyTrainingCalendarController::class, 'allEvent'])->name('yearly-training-calendar.all-event');
    Route::get('fiscal-year', [App\Http\Controllers\Frontend\YearlyTrainingCalendarController::class, 'fiscalYear'])->name('fiscal-year');
    Route::get('venue-list/{id}', [App\Http\Controllers\Frontend\YearlyTrainingCalendarController::class, 'venueList'])->name('venue-list');

    Route::post('venue-list-search', [App\Http\Controllers\Frontend\YearlyTrainingCalendarController::class, 'venueListSearch'])->name('venue-list-search');

    Route::get('gallery-categories/{galleryCategory}', [\App\Http\Controllers\Frontend\GalleryCategoryPageController::class, 'singleGalleryCategoryPage'])->name('gallery-category');
    Route::get('gallery-categories', [\App\Http\Controllers\Frontend\GalleryCategoryPageController::class, 'allGalleryCategoryPage'])->name('gallery-categories');

    Route::get('events/{event}', [\App\Http\Controllers\Frontend\EventPageController::class, 'singleEventPage'])->name('single-event');
    Route::get('pages/{page_id}', [App\Http\Controllers\Frontend\StaticContentController::class, 'index'])
        ->name('static-content.show');

    Route::post('course-running-batches', [\App\Http\Controllers\CourseController::class, 'checkRunningBatch'])->name('course-running-batches');
};

Route::group(['as' => 'frontend.'], $routesWithoutInstituteSlug);
Route::group(['prefix' => '{instituteSlug?}', 'as' => 'frontend.', 'middleware' => ['detect-institute']], $routesWithoutInstituteSlug);
