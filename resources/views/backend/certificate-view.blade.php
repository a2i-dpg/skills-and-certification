<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" >
    <link href="https://fonts.googleapis.com/css2?family=Waterfall&display=swap" rel="stylesheet">
</head>
<body>

<style >
    body {
        background-image: url("{{$certificateBackground}}");
        background-repeat: no-repeat;
        background-size: 100% 100% ;
    }
    h1{
        position: absolute;
        top: 420px;
        text-align: center;
        font-size: 80px;
        font-family: cursive;
        font-weight: bold;
        width: 100%;
    }
    .logo{
        position: absolute;
        top: 100px;
        left: 200px;
        font-family: cursive;
        width: 100%;
    }
    .details-center{
        position: absolute;
        top: 600px;
        left: 500px;
        width:650px;
        text-align: center;
        font-size: 20px;
        line-height: 30px;
        font-family: HelveticaNeue-Light, Helvetica Neue Light, Helvetica Neue, Helvetica, Arial, Lucida Grande, serif;
        font-weight: bold;
    }
    .date-section{
        position: absolute;
        top: 840px;
        left: 340px;
        text-align: center;
        font-size: 30px;
    }
    .signature-section{
        position: absolute;
        top: 800px;
        left: 1000px;
    }
    .custom_css {
        {{$custom_css}}
    }
</style>
{{--<p class="logo">
    <img style="border-radius: 50%; width: 150px;height: 150px;" src="{{$institute_logo}}" alt="institute">
</p>--}}
<div class="custom_css">

    <p class="name-center">
    <h1>{{$name}}</h1>
    </p>
    <p class="details-center">
        Mr./Mrs. {{$name}} son/daughter of Mr. {{$father}}
        has successfully completed the course from our Institute {{$institute}}.
    </p>
    <p class="date-section">
        {{date('dS F Y')}}
    </p>
    <p class="signature-section">
        @if($image)
            <img style="width: 400px;height: 80px;" src="{{$image}}" alt="signature"/>
        @endif
    </p>
</div>

</body>
</html>
