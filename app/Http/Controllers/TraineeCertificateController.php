<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\Trainee;
use App\Models\TraineeCourseEnroll;
use App\Models\Batch;
use App\Models\BatchCertificate;
use App\Models\CertificateRequest;
use App\Models\TraineeCertificate;
use App\Services\TraineeCertificateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use App\Helpers\Classes\AuthHelper;

use App\Events\EnrollEmailEvent;


class TraineeCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    const VIEW_PATH = 'backend.trainee-certificates.';
    public TraineeCertificateService $traineeCertificateService;

    /**
     * CourseController constructor.
     * @param TraineeCertificateService $traineeCertificateService
     */

    public function __construct(TraineeCertificateService $traineeCertificateService)
    {
        $this->traineeCertificateService = $traineeCertificateService;
    }

    /**
     * @return View
     */
    public function index() :View
    {
        return \view(self::VIEW_PATH . 'browse');
    }


    /**
     * @param int $id
     * @return View
     */
    public function create(int $id): View
    {
        $batchCertificate = BatchCertificate::find($id)->with('batch')->first();
        return \view(self::VIEW_PATH . 'edit-add', compact('batchCertificate','id'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBatchCertificateDatatable(Request $request): JsonResponse
    {
        return $this->traineeCertificateService->getBatchCertificateLists();
    }

    /**
     * @param int $batchId
     * @return View
     */
    public function certificateEdit(int $batchId) :View
    {
        $authUser = AuthHelper::getAuthUser();
        $certificateTemplate = CertificateTemplate::select('*');
        if($authUser->isUserBelongsToInstitute()){
            $certificateTemplate->where('institute_id', '=', $authUser->institute_id);
        }
        $certificateTemplates = $certificateTemplate->get();

        $batchCertificate = BatchCertificate::where('batch_id',$batchId)->first();
        $batch = Batch::where('id','=',$batchId)->with('course')->first();
        $batch_id = $batchId;
        return \view(self::VIEW_PATH . 'edit-add-certificate', compact('batch','batchCertificate','batchId','batch_id','certificateTemplates'));

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request):RedirectResponse
    {
        //dd($request->all());
        //dd($request);
        $traineeCertificateValidatedData = $this->traineeCertificateService->validator($request)->validate();
        //dd($traineeCertificateValidatedData);
         try {
             $this->traineeCertificateService->createBatchCertificate($traineeCertificateValidatedData);
         } catch (\Throwable $exception) {
             Log::debug($exception->getMessage());
             return back()->with([
                 'message' => __('generic.something_wrong_try_again'),
                 'alert-type' => 'error'
             ])->withInput();
         }

         return redirect()->route('admin.batches.certificates.index')->with([
             'message' => __('generic.object_created_successfully', ['object' => 'BatchCertificate']),
             'alert-type' => 'success'
         ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getDatatable(Request $request, int $id): JsonResponse
    {
        return $this->traineeCertificateService->getBatchCertificateLists($id);

    }

    /**
     * @return View
     */
    public function certificateRequestIndex() :View
    {
        return \view(self::VIEW_PATH . 'certificate-request.browse');
    }

    /**
     * @return View
     */
    public function certificateRequestAcceptedIndex() :View
    {
        return \view(self::VIEW_PATH . 'certificate-request.browse-accepted-request');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function certificateRequestDatatable(Request $request): JsonResponse
    {
        return $this->traineeCertificateService->getCertificateRequestDatatable($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function certificateRequestAcceptedDatatable(Request $request): JsonResponse
    {
        return $this->traineeCertificateService->getAcceptedCertificateRequestDatatable();
    }

    /**
     * @param int $id
     * @return View
     */
    public function certificateRequestView(int $id) :View
    {
        $certificateRequest = CertificateRequest::select('certificate_requests.id as id', 'certificate_requests.name as given_name',
            'certificate_requests.date_of_birth as given_date_of_birth', 'certificate_requests.father_name as given_father_name',
            'certificate_requests.mother_name as given_mother_name','certificate_requests.id_image as id_image', 'trainees.name as registered_name',
            'trainees.date_of_birth as registered_date_of_birth', 'batches.title as batch_title', 'courses.title as course_title')
        ->leftJoin('trainee_batches', 'trainee_batches.id', '=', 'certificate_requests.trainee_batch_id')
        ->leftJoin('batches', 'batches.id', '=', 'trainee_batches.batch_id')
        ->leftJoin('trainees', 'trainees.id', '=', 'certificate_requests.trainee_id')
        ->leftJoin('courses', 'courses.id', '=', 'batches.course_id')->where('certificate_requests.id',$id)->first();

        return \view(self::VIEW_PATH . 'certificate-request.edit-add' , compact('certificateRequest'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function certificateRequestReview(Request $request):RedirectResponse
    {
        
        try {
            DB::beginTransaction();
            $status = !empty($request->approve_status)?CertificateRequest::ACCEPTED: CertificateRequest::REJECTED;
            $updateData= [
                "comment" => $request->comment,
                "row_status"=>$status
            ];
            //nedd to send email
            // $certificateRequest = CertificateRequest::find($request->certificate_request_id);
            // $trainee = Trainee::find($certificateRequest->trainee_id);
            // $traineeCourseEnroll = TraineeCourseEnroll::where(['trainee_id'=>$trainee->id])->first();
            // $course = Course::where(['id'=>$traineeCourseEnroll->course_id])->first();
            // $email_data = [
            //     'email' => $trainee->email,
            //     'name' => $trainee->name,
            //     'course' => $course->title,
            //     'subject' => 'Requested certificate confirmation email',
            //     'view' => 'frontend.email.trainee-request-certificate-reject-email',
            //     'from' => env('MAIL_FROM_ADDRESS'),
            // ];
            // event(new EnrollEmailEvent($email_data));
            // dd($email_data);

            

            if(!empty($request->approve_status)){
                //Approved
                $certificateRequest = CertificateRequest::find($request->certificate_request_id);

                $batch_certificates = BatchCertificate::select('batch_certificates.id', 'batch_certificates.batch_id')
                    ->leftJoin( 'trainee_course_enrolls', 'trainee_course_enrolls.batch_id','=','batch_certificates.batch_id')
                    ->where('trainee_course_enrolls.id','=',$certificateRequest->trainee_course_enrolls_id)->first();


                if(empty($batch_certificates)){
                    DB::rollBack();
                    return back()->with([
                        'message' => __('generic.create_certificate'),
                        'alert-type' => 'error'
                    ])->withInput();
                }

                $data = [
                    'batch_certificate_id' => $batch_certificates->id,
                    'certificate_request_id' =>$request->certificate_request_id,
                    'trainee_course_enroll_id' => $certificateRequest->trainee_course_enrolls_id,
                    'name' => $certificateRequest->name,
                    'father_name' => $certificateRequest->father_name,
                    'mother_name' => $certificateRequest->mother_name,
                    'batch_id' => $batch_certificates->batch_id,
                    'trainee_id' => $certificateRequest->trainee_id,
                    'date_of_birth' => $certificateRequest->date_of_birth,
                    'uuid' => $certificateRequest->id,
                    'row_status' => 1,
                ];

                $traineeCertificate = TraineeCertificate::where('trainee_course_enroll_id','=',$certificateRequest->trainee_course_enrolls_id)->first();

                if (empty($traineeCertificate)){
                    TraineeCertificate::create($data);
                }else{
                    TraineeCertificate::where('trainee_course_enroll_id','=',$certificateRequest->trainee_course_enrolls_id)
                        ->update($data);
                }
                CertificateRequest::find($request->certificate_request_id)->update($updateData);

                //nedd to send email
                $certificateRequest = CertificateRequest::find($request->certificate_request_id);
                $trainee = Trainee::find($certificateRequest->trainee_id);
                $traineeCourseEnroll = TraineeCourseEnroll::where(['trainee_id'=>$trainee->id])->first();
                $course = Course::where(['id'=>$traineeCourseEnroll->course_id])->first();
                $email_data = [
                    'email' => $trainee->email,
                    'name' => $trainee->name,
                    'course' => $course->title,
                    'subject' => 'Requested certificate confirmation email',
                    'view' => 'frontend.email.trainee-request-certificate-accept-email',
                    'from' => env('MAIL_FROM_ADDRESS'),
                ];
                event(new EnrollEmailEvent($email_data));

            }else{
                //Rejected
                CertificateRequest::find($request->certificate_request_id)->update($updateData);

                //nedd to send email
                $certificateRequest = CertificateRequest::find($request->certificate_request_id);
                $trainee = Trainee::find($certificateRequest->trainee_id);
                $traineeCourseEnroll = TraineeCourseEnroll::where(['trainee_id'=>$trainee->id])->first();
                $course = Course::where(['id'=>$traineeCourseEnroll->course_id])->first();
                $email_data = [
                    'email' => $trainee->email,
                    'name' => $trainee->name,
                    'course' => $course->title,
                    'subject' => 'Requested certificate confirmation email',
                    'view' => 'frontend.email.trainee-request-certificate-reject-email',
                    'from' => env('MAIL_FROM_ADDRESS'),
                ];
                event(new EnrollEmailEvent($email_data));
            }

            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ])->withInput();
        }
        
        return redirect()->route('admin.trainee.certificates.request')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Certificate']),
            'alert-type' => 'success'
        ]);

    }

}
