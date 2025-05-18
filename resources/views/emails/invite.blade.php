@component('mail::message')
# You're Invited

You have been invited to join the system. Click the button below to accept your invite.

@component('mail::button', ['url' => $url])
Accept Invitation
@endcomponent

This link will expire in 7 days.

Thanks,  
{{ config('app.name') }}
@endcomponent
