<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body style="font-family: tahoma, sans-serif; direction: rtl; text-align: right;">
    <h2>سلام!</h2>
    <p>شما به ورک اسپیس <strong>{{ $workspacename }}</strong> با نقش <strong>{{ $userrole }}</strong> دعوت شده اید</p>
    <p>
        <a href="{{ url('/invitations/' . $token . '/accept') }}">پذیرش دعوت</a>
    </p>
</body>

</html>