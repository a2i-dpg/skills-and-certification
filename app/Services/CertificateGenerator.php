<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Models\Certificate;


class CertificateGenerator
{
    public function generateCertificate(string $template, array $traineeInfo): string
    {
        $path = $traineeInfo['path'] . "/" . $traineeInfo['register_no'] . ".pdf";
        if (!file_exists("storage/" . $path)) {
            $pdf = App::make('dompdf.wrapper');
            $certificate = $pdf->loadView($template, compact('traineeInfo'));
            $pdf = $certificate->setPaper('A4-L', 'landscape')->setWarnings(false)->download();
            Storage::path(Storage::disk('public')->put($path, $pdf));
            $courseWiseCertificateData = [
                'publish_course_id' => $traineeInfo['publish_course_id'],
                'trainee_id' => $traineeInfo['trainee_id'],
                'certificate_path' => $path,
            ];
            Certificate::create($courseWiseCertificateData);
        }
        return $path;
    }
}
