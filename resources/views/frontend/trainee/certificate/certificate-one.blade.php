<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificate of {{ !empty($traineeInfo)? $traineeInfo['trainee_name']:'' }}</title>
    <style>
        @font-face {
            font-family: 'examplefont';
            src: url('{{public_path('assets/font/MonotypeCorsiva.ttf')}}');
        }

        body {
            font-family: serif;
            font-weight: 400;
            color: #322d28;
            padding: 15px;
        }

        table, th, td {
            border-collapse: collapse;
        }

        .example_font {
            font-family: examplefont, serif;
            font-size: 20px;
        }

        #background-watermark {
            background-image: url('{{public_path('assets/logo/certificate_watermark.png')}}');
            background-repeat: no-repeat;
            background-position: center;
            width: 90%;
            margin: 0 auto;
            page-break-inside: avoid;
        }

        @page {
            margin: auto;
        }
    </style>
</head>
<body>

<div style="background: #8a5d3b;">
    <div style="border-top-left-radius: 150px;border-bottom-right-radius: 150px;background: white;">
        <div style="width: 100%;">
            <table id="background-watermark">
                <tr>
                    <td style="width:20%; padding-top: 10px;text-align: right;"><img
                            src="{{public_path('assets/logo/certificate_logo.png')}}" style="" width="70px"
                            height="70px" alt=""></td>
                    <td colspan="3" style="width:80%; text-align:center; padding-top: 20px;"><h3
                            style="margin: 0;text-align: center;">Bangladesh Industrial Technical Assistance Center
                            (BITAC)</h3>
                        <h4 style="text-align:center;font-weight: 400; margin: 0; margin-bottom: 5px;">Ministry of
                            Industries</h4>
                        <h4 style="text-align:center; font-weight: 400; margin-top: 5px;">Government of the People's
                            Republic
                            of Bangladesh </h4></td>
                    <td style="width:20%; padding-top: 10px;"><img
                            src="{{public_path('assets/logo/certificate_logo.png')}}"
                            style="text-align:center;" width="70px" height="70px"
                            alt=""></td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-top: 70px; padding-bottom: 70px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 30%;"><span style="font-size: 20px;padding-bottom: 5px">No</span>
                                    <span
                                        style="border-bottom: 2px dotted;font-size: 16px;">{{ !empty($traineeInfo)? $traineeInfo['register_no']:'' }}</span>
                                </td>
                                <td style="width: 40%;text-align: center;"><h3 style=" color: #8a5d3b;margin: 0;">
                                        CERTIFICATE</h3></td>
                                <td style="width: 30%;margin-left: 20px;"><span style="font-size: 20px; padding-bottom: 5px">Date: </span>
                                    <span
                                        style="border-bottom: 2px dotted;font-size: 16px;"> {{ !empty($traineeInfo)? $traineeInfo['to_date']:'' }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 11%;"><span
                                        style="margin: 0;" class="example_font">Certified that</span>
                                </td>
                                <td style="text-align: center;border-bottom: 3px dotted;"><span
                                        style="text-align: center;font-size: 18px;font-style: italic;">
                                        <b>{{ !empty($traineeInfo)? $traineeInfo['trainee_name']:'' }}</b>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style=" padding-top: 20px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 13%;"><span
                                        style="margin: 0;" class="example_font">son/daughter of </span>
                                </td>
                                <td style="width: 69%; text-align:center; border-bottom: 3px dotted;"><span
                                        style="text-align: center;font-size: 18px;font-style: italic;">
                                        <b>{{ !empty($traineeInfo)? $traineeInfo['trainee_father_name']:'' }}</b>
                                    </span>
                                </td>
                                <td style=""><span
                                        style="text-align:right; margin: 0;" class="example_font">has attended</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-top: 20px;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 29%;"><span
                                        style="margin: 0;"
                                        class="example_font">the Technical Training Programme on</span>
                                </td>
                                <td style=" border-bottom: 3px dotted; text-align: center; font-size: 18px;font-style: italic;">
                                    <b>{{ !empty($traineeInfo)? $traineeInfo['publish_course_name']:'' }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style=" padding-top: 20px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;"><span
                                        style="margin: 0;" class="example_font">from</span>
                                </td>
                                <td style=" border-bottom: 3px dotted; text-align: center; font-size: 18px;font-style: italic;">
                                    <b>{{ !empty($traineeInfo)? $traineeInfo['from_date']:'' }}</b>
                                </td>
                                <td style="width: 3%;"><span
                                        style="margin: 0;" class="example_font">to</span>
                                </td>
                                <td style=" border-bottom: 3px dotted; text-align: center; font-size: 18px;font-style: italic;">
                                    <b>{{ !empty($traineeInfo)? $traineeInfo['to_date']:'' }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 20px;padding-bottom: 30px;" colspan="3"><span
                            style="margin: 0;" class="example_font">
                            We wish him/her every success in life.</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; padding-bottom: 50px">
                        <img src="{{ file_exists(public_path($traineeInfo['course_coordinator_signature']))? public_path($traineeInfo['course_coordinator_signature']):'' }}" height="40px">
                        <p style="font-size: 16px; font-weight: bold;">COURSE COORDINATOR</p>
                    </td>
                    <td></td>
                    <td colspan="2" style="text-align: right; padding-bottom: 50px">
                        <img src="{{ file_exists(public_path($traineeInfo['course_director_signature']))? public_path($traineeInfo['course_director_signature']):'' }}" height="40px">
                        <p style="font-size: 16px; font-weight: bold">COURSE DIRECTOR</p>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
</body>
</html>
