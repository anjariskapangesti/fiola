<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ asset('vendor/mail/default.css') }}">
    <title>{{ $subject }}</title>
</head>

<body>
    *ini adalah pesan otomatis <br><br>
    Dear {{ $role }} Department, <br>
    <strong>Reminder</strong><br><br>

    Terkait Form ITD, terdapat {{ $data }}. Mohon diapprove/bisa tekan tombol More Details untuk masuk ke
    website FIOLA. <br><br>

    @component('mail::button', ['url' => 'https://fiola.aiia.co.id'])
        More Details
    @endcomponent
    Terima kasih,<br>
    AISINBISA
</body>

</html>
