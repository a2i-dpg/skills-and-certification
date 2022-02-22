<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: "nikosh";
            font-weight: 400;
            color: #322d28;
            margin: 0;

        }

        table, th, td {
            border-collapse: collapse;
        }

        table {

        }

        @page {
            margin: 0;
        }
    </style>
</head>
<body>

<div style="background: #8a5d3b; height:100%;">
    <div style="border-top-left-radius: 150px;border-bottom-right-radius: 150px;background: white; height:100%;">
        <div style="width: 1140px;">
            <table width='90%'
                   style="margin: 0 auto; background: url('{{public_path('asset/watermark.png')}}') no-repeat center; "
                   cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:20%; padding-top: 10px;text-align: right;"><img
                            src="{{public_path('asset/logo.png')}}" style="" width="70px" height="70px" alt=""></td>
                    <td colspan="3" style="width:80%; text-align:center; padding-top: 20px;"><h3
                            style="margin: 0;text-align: center;">Bangladesh Industrial Technical Assistance Center
                            (BITAC)</h3>
                        <h4 style="text-align:center;font-weight: 400; margin: 0; margin-bottom: 5px;">Ministry of
                            Industries</h4>
                        <h4 style="text-align:center; font-weight: 400; margin-top: 5px;">Government of the People's
                            Republic
                            of Bangladesh </h4></td>
                    <td style="width:20%; padding-top: 10px;"><img src="{{public_path('asset/logo.png')}}"
                                                                   style="text-align:center;" width="70px" height="70px"
                                                                   alt=""></td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-top: 100px; padding-bottom: 100px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 30%;"><span style="font-size: 20px;">No</span>
                                    <span style="border: 4px;border-style: none none dotted none;font-size: 16px;">123456789</span>
                                </td>
                                <td style="width: 40%;text-align: center;"><h3 style=" color: #8a5d3b;margin: 0;">
                                        CERTIFICATE</h3></td>
                                <td style="width: 30%;text-align: right;"><span style="font-size: 20px;">Date: </span>
                                    <span
                                        style="border: 4px;border-style: none none dotted none;font-size: 16px; display: inline-block;">10/10/20</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="5">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 15%;"><span
                                        style="margin: 0;font-size: 20px;font-style: italic;font-weight: bold;">Certified that</span>
                                </td>
                                <td style="text-align: center;border-bottom: 4px dotted;"><span
                                        style="text-align: center;font-size: 18px;font-style: italic;"><span>John Doe</span></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style=" padding-top: 20px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 17%;"><span
                                        style="margin: 0;font-size: 20px;font-style: italic;font-weight: bold;">son/daughter of </span>
                                </td>
                                <td style="width: 69%; text-align:center; border-bottom: 4px dotted;"><span
                                        style="text-align: center;font-size: 18px;font-style: italic;"><span>Mark Doe</span></span>
                                </td>
                                <td style=""><span
                                        style="text-align:right;font-size: 20px;margin: 0;font-style: italic;font-weight: bold;">has attended</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-top: 20px;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 39%;"><span
                                        style="margin: 0;font-size: 20px;font-style: italic;font-weight: bold;">the Technical Training Programme on</span>
                                </td>
                                <td style=" border-bottom: 4px dotted; text-align: center; font-size: 18px;font-style: italic;">
                                    <span>BITAC</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style=" padding-top: 20px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 6%;"><span
                                        style="margin: 0;font-size: 20px;font-style: italic;font-weight: bold;">from</span>
                                </td>
                                <td style=" border-bottom: 4px dotted; text-align: center; font-size: 18px;font-style: italic;">
                                    12/12/21
                                </td>
                                <td style="width: 3%;"><span
                                        style="margin: 0;font-size: 20px;font-style: italic;font-weight: bold;">to</span>
                                </td>
                                <td style=" border-bottom: 4px dotted; text-align: center; font-size: 18px;font-style: italic;">
                                    12/12/21
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 50px;" colspan="3"><span
                            style="margin: 0;font-size: 20px;font-style: italic;font-weight: bold;">We wish him/her
                            every success in life</span></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; padding-top: 120px;"><span
                            style="font-size: 16px; font-weight: bold;">COURSE COORDINATOR</span></td>
                    <td></td>
                    <td colspan="2" style="text-align: right;  padding-top: 120px;"><span
                            style="font-size: 16px; font-weight: bold">COURSE DIRECTOR</span></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
