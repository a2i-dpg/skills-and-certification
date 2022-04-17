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
        You have requested certificate for {{@$email_data['course']}} course, <br>
        Thank you so much for requesting certificate. <br>
        we will inform you very soon your certificate requesting status <br>
        
        Best regards,  <br>
        {{env('APP_NAME')}} <br>
    </p>
</div>


</body>
</html>
