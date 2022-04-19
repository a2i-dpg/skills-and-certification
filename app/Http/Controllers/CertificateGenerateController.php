<?php

namespace App\Http\Controllers;

use App\Models\BatchCertificate;
use App\Models\Course;
use App\Models\TraineeCertificate;
use App\Models\TraineeCourseEnroll;

class CertificateGenerateController extends Controller
{
    public function generatePDF($traineeCourseEnrollId)
    {
        $traineeCourseEnroll = TraineeCourseEnroll::find($traineeCourseEnrollId);
        //$course = Course::find($traineeCourseEnroll->course_id)->with('institute')->firstOrFail();
        $course = Course::with('institute')->where('id',$traineeCourseEnroll->course_id)->first();
        $traineeCertificate = TraineeCertificate::where('trainee_course_enroll_id', $traineeCourseEnrollId)->first();
        $batchCertificate = BatchCertificate::where('batch_id', $traineeCourseEnroll->batch_id)->first();

        //dd($traineeCourseEnroll);
        //dd($course);

        $name = $traineeCertificate->name;
        $father = $traineeCertificate->father_name;
        $mother = $traineeCertificate->mother_name;

        $tamplate = '';
        if ($batchCertificate->tamplate == 1) {
            $tamplate = public_path('assets/certificateTemplate/certificate-1.jpg');
        } else if ($batchCertificate->tamplate == 2){
            $tamplate = public_path('assets/certificateTemplate/certificate-2.jpg');
        } else if ($batchCertificate->tamplate == 3){
            $tamplate = public_path('assets/certificateTemplate/certificate-3.jpg');
        }
        
        $data = [
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
