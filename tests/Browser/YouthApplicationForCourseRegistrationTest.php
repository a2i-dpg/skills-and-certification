<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class YouthApplicationForCourseRegistrationTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testApplication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/youth-registrations')
                ->type('name', 'PK Chaki')
                ->type('mobile', '01757808214')
                ->select2('select[name=institute_id]', 'BITAC')
                //->select2('select[name=branch_id]', 'Bashundhara Branch')
                ->pause(3000)
                ->select2('select[id=course_id]', 'Graphic Design')
                ->select2('select[name=nationality]', 'Bangladeshi')
                //->select2('select[name=freedom_fighter_status]', 'Non freedom fighter')
                ->check('#religion_hindu_label')
                ->check('#marital_status_single_label')
                ->type('nid', '12345678901')
                ->check('#ethnic_group_no_label')
                ->check('#gender_male_label')
                //->check('#physically_not_disable_label')
                ->attach('student_pic', 'public/assets/testImageDummy/300x300.jpg')
                ->attach('student_signature_pic', 'public/assets/testImageDummy/300x80.jpg')
                ->select2('select[id=present_address_division_id]', 'ঢাকা')
                ->select2('select[id=present_address_district_id]', 'ঢাকা')
                ->select2('select[id=present_address_upazila_id]', 'তেজগাঁও উন্নয়ন সার্কেল')
                ->select2('select[id=permanent_address_division_id]', 'ঢাকা')
                ->select2('select[id=permanent_address_district_id]', 'ঢাকা')
                ->select2('select[id=permanent_address_upazila_id]', 'তেজগাঁও উন্নয়ন সার্কেল')
                ->type('address[present][present_address_house_address][postal_code]', 2222)
                ->type('address[present][present_address_house_address][village_name]', '2')
                ->type('address[present][present_address_house_address][house_and_road]', '2')
                ->type('address[permanent][permanent_address_house_address][postal_code]', 2222)
                ->type('address[permanent][permanent_address_house_address][village_name]', '2')
                ->type('address[permanent][permanent_address_house_address][house_and_road]', '2')
                ->type('familyMember[father][member_name]', 's Chaki')
                ->type('familyMember[father][nid]', '2222222222')
                ->type('familyMember[father][mobile]', '01700000000')
                ->type('familyMember[mother][member_name]', 's Chaki')
                ->type('familyMember[mother][nid]', '2222222222')
                ->type('familyMember[mother][mobile]', '01700000000');

            $browser->script('document.querySelector("#date_of_birth").value="2000-02-20";');
            $browser->script('document.querySelector("#mothers_date_of_birth").value="2000-02-20";');
            $browser->script('document.querySelector("#fathers_date_of_birth").value="2000-02-20";');
            $browser->press('Apply Now')
                ->assertSchemeIsNot('/youth-registrations');
            //->assertPathBeginsWith('/youth-registration/success');
        });
    }
}
