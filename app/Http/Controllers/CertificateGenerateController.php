<?php

namespace App\Http\Controllers;

use App\Models\BatchCertificate;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Trainee;
use App\Models\TraineeCertificate;
use App\Models\TraineeCourseEnroll;

use App\Helpers\Classes\AuthHelper;

class CertificateGenerateController extends Controller
{
    public function generatePDF($traineeCourseEnrollId)
    {
        $traineeCourseEnroll = TraineeCourseEnroll::find($traineeCourseEnrollId);
        //$course = Course::find($traineeCourseEnroll->course_id)->with('institute')->firstOrFail();
        $course = Course::with('institute')->where('id',$traineeCourseEnroll->course_id)->first();
        $traineeCertificate = TraineeCertificate::where('trainee_course_enroll_id', $traineeCourseEnrollId)->first();
        $batchCertificate = BatchCertificate::with('certificateTemplate')->where('batch_id', $traineeCourseEnroll->batch_id)->first();

        // auth validation as if anonymous user can not download any certificate
        $authUser = AuthHelper::getAuthUser();
        $isAuthTrainee = AuthHelper::isAuthTrainee();

        if (!$isAuthTrainee && !$authUser) {
            return redirect()->route('frontend.main')->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        




        if ($isAuthTrainee == true) {
            $trainee = Trainee::getTraineeByAuthUser(); 
            $trainee_id = $traineeCertificate->trainee_id;
            if ($trainee->id != $trainee_id) {
                return redirect()->route('frontend.trainee')->with([
                    'message' => __('generic.something_wrong_try_again'),
                    'alert-type' => 'error'
                ]);
            }
        } else if($authUser) {
            
            if (($authUser->user_type_id != 1) && ($authUser->user_type_id != 2) ) {
                $batch = Batch::find($batchCertificate->batch_id);
                if ($batch->institute_id != $authUser->institute_id) {
                    return redirect()->route('admin.trainee.certificates.request.accepted')->with([
                        'message' => __('generic.something_wrong_try_again'),
                        'alert-type' => 'error'
                    ]);
                }
            }
            
        }
        // auth validation as if anonymous user can not download any certificate
        
        

        $name = $traineeCertificate->name;
        $father = $traineeCertificate->father_name;
        $mother = $traineeCertificate->mother_name;

        $tamplate = '';

        if(!$batchCertificate->certificate_template_id){
            if ($batchCertificate->tamplate == 1) {
                $tamplate = public_path('assets/certificateTemplate/certificate-1.jpg');
            } 
            else if ($batchCertificate->tamplate == 2){
                $tamplate = public_path('assets/certificateTemplate/certificate-2.jpg');
            } 
            else if ($batchCertificate->tamplate == 3){
                $tamplate = public_path('assets/certificateTemplate/certificate-3.jpg');
            }
        }else{
            $tamplate = public_path('storage/'.$batchCertificate->certificateTemplate->image);
        }

        $data = [
            'custom_css' => ($batchCertificate->custom_css) ? $batchCertificate->custom_css : '',
            'name' => ucwords($name),
            'father' => ucwords($father),
            'mother' => ucwords($mother),
            'institute' => ucwords($course->institute->title),
            'image' => empty($batchCertificate->signature) ? '' : $this->convertImageToBase64(public_path('storage/'.$batchCertificate->signature)),
            'certificateBackground' =>  $tamplate,
            'institute_logo' => !empty(optional($course->institute)->logo) ? $this->convertImageToBase64(public_path('storage/'. $course->institute->logo)) : asset('/assets/certificateTemplate/Original-and-certified.jpg'),
        ];

        $customPaper = array(0, 0, 919, 1300);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('backend.certificate-view', $data)
            ->setPaper($customPaper, 'landscape');
        return $pdf->stream('certificate.pdf');
    }
    public function convertImageToBase64($path){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/jpg;base64,' . base64_encode($data);
        return $base64;
    }
}
