<!DOCTYPE html>
<html>
<head>
    <title>NISE3 Training Management System</title>
</head>
<body>
<div class="p-0 m-0">
    <p class="p-0 m-0">
        Dear Miss/Mr. {{ $traineeName }}, <br>
        &nbsp;<br>
        {!! $msg !!}</p>
    <p>
    <h4 class="lead">Your account access key is
        <b style="color: #0d4cda;
            background: #6eff001f;
            padding: 5px 10px;
            font-size: 15px;
            letter-spacing: 5px;
            border-radius: 5px;">
            {{ $accessKey }}
        </b>
    </h4>
    </p>
    <br>
    <p>
        Thanks, <br>
        &nbsp;<br>
    </p>
</div>


</body>
</html>
