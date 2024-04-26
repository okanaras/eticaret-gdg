<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hos Geldin</title>
</head>

<body>

    <p>
        Merhaba {{ $user->name }},
        <br>
        Hos geldiniz.
    </p>

    <p>
        Asagidaki mailimi dogrula butonuna tiklayarak mailinizi dogrulayabilirsiniz.
    </p>

    <hr>

    <center><a href="{{ route('verify', ['token' => $token]) }}">Mailimi Dogrula</a></center>
</body>

</html>
