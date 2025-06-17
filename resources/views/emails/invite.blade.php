@php
    $url = route('register.invited', ['token' => $invite->token]);
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>You're Invited</title>
</head>
<body>
    <h1>You're Invited</h1>

    <p>You have been invited to join the system. Click the button below to accept your invite.</p>

    <p>
        <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 4px;">
            Accept Invitation
        </a>
    </p>

    <p>This link will expire in 7 days.</p>

    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
