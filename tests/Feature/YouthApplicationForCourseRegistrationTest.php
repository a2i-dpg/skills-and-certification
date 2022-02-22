<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class YouthApplicationForCourseRegistrationTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_youth_registration_for_course()
    {
        $response = $this->post(route('youth-registrations.store'), [
            '_token' => csrf_token(),
            "name" => "xcv",
            "institute_id" => "1",
            "branch_id" => "1",
            "course_id" => "1",
            "gender" => "1",
            "mobile" => "01757808214",
            "date_of_birth" => "2021-06-01",
            "marital_status" => "2",
            "religion" => "2",
            "nationality" => "bd",
            "nid" => "12345678rt",
            "ethnic_group" => "2",
            "address" => [
                "present" => [
                    "present_address_division_id" => "3",
                    "present_address_district_id" => "18",
                    "present_address_upazila_id" => "113",
                    "present_address_house_address" => [
                        "postal_code" => "1216",
                        "village_name" => "dfsg",
                        "house_and_road" => "dsfg2",
                    ],
                ],
                "permanent" => [
                    "permanent_address_division_id" => "3",
                    "permanent_address_district_id" => "18",
                    "permanent_address_upazila_id" => "113",
                    "permanent_address_house_address" => [
                        "postal_code" => "1216",
                        "village_name" => "dfsg",
                        "house_and_road" => "dsfg2",
                    ],
                ],
            ],
            "permanent_address_same_as_present_address" => "on",
            "familyMember" => [
                "father" => [
                    "member_name" => "xcv",
                    "date_of_birth" => "2021-06-01",
                    "nid" => "234234234234",
                    "mobile" => "01757808214",
                    "relation_with_youth" => "Father",
                ],
                "mother" => [
                    "member_name" => "xcv",
                    "date_of_birth" => "2021-06-01",
                    "nid" => "1234567899",
                    "mobile" => "01757808214",
                    "relation_with_youth" => "Mother",
                ],
            ],
            'student_pic' => UploadedFile::fake()->image('avatar.jpg'),
            'student_signature_pic' => UploadedFile::fake()->image('avatar.jpg'),
        ]);
        //dd($response->assertSessionHasNoErrors());

    }
}
