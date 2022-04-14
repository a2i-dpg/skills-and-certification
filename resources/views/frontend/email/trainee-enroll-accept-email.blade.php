<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div class="p-0 m-0">
    <p class="p-0 m-0">
        Dear {{ strtoupper(@$email_data['name']) }},
        &nbsp;
    </p>

    <p> 
        Congratulations you have accepted for {{@$email_data['course']}} course <br>
        
        Best regards,  <br>
        {{env('APP_NAME')}} <br>
    </p>
</div>


</body>
</html>
