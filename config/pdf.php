<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('../temp/'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => '',
    'font_path' => base_path('resources/pdf-fonts/'),
    'font_data' => [
        'examplefont' => [
            'R'  => 'MonotypeCorsiva.ttf',    // regular font
 //           'R'  => 'ten.ttf',    // regular font
            //'I'  => 'PinyonScript-Regular.ttf',    // regular font
//            'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
//            'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
//            'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
            //'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            //'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
        // ...add as many as you want.
    ]
];
