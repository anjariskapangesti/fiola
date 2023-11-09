<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('mail/default.css') }}">
    <title>{{ $subject }}</title>
</head>
<body>
    @component('mail::message')
    # FIOLA

    Reminder

    @component('mail::button', ['url' => 'https://fiola.aiia.co.id'])
        More Details
    @endcomponent

    Thanks,<br>
    ITD
@endcomponent
</body>
</html>