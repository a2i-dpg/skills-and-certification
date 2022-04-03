<?php

namespace Database\Seeders;

use App\Models\QuestionAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $table = (new QuestionAnswer())->getTable();
        DB::table($table)->truncate();

        \DB::table($table)->insert(array(
            [
                //'institute_id' => 0,
                'row_status' => 1,
                "question" => "Question 1: What are center based training trades?",
                "answer" => 'Answer: Please see the courses page  ( <a target="_blank" href="' . route('frontend.course_search') . '">Click here</a> ) ',
            ],
            [
                //'institute_id' => 0,
                'row_status' => 1,
                "question" => "Question 2: Where can I contact for details about training?",
                "answer" => "Answer: See the contact page ( <a target='_blank' href='" . route('frontend.contact-us-page') . "'>Click here</a> )",
            ],
            [
                //'institute_id' => 0,
                'row_status' => 1,
                "question" => "Question 3: Where and how to get admission form for training?",
                "answer" => "Answer: Apply for admission to your preferred course from the online application page ( <a target='_blank' href='#'>Apply online by clicking </a>)",
            ],
            [
                //'institute_id' => 0,
                'row_status' => 1,
                "question" => "Question 4: Do you have the opportunity to receive training from home?",
                "answer" => "Ans: All the training is industry based technical training. So there is no opportunity to sit at home.",
            ],
            [
                //'institute_id' => 0,
                'row_status' => 1,
                "question" => "Question 5: Whether there is any training course online",
                "answer" => "Answer :  Training can be applied for online but the training has to come to the designated center of BITAC.",
            ],
        ));
        Schema::enableForeignKeyConstraints();
    }
}
