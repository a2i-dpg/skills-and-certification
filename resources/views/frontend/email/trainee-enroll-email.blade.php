<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div class="p-0 m-0">
    <p class="p-0 m-0">
        Dear {{ strtoupper(@$trainee['name']) }},
        &nbsp;
    </p>

    <p> 
        You have enrolled to course {{@$trainee['title']}} , <br>
        Thank you so much for enrolling to this course.<br>
        &nbsp;<br>
        Best regards,  <br>
        {{env('APP_NAME')}}
        &nbsp;<br>
    </p>
</div>


</body>
</html>
