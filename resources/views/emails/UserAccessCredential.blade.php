@component('mail::message')
# Welcome {{ $details['name'] }}

Your email: {{ $details['email'] }}
Your Password: {{ $details['password'] }}

If you did not create an account, no further action is required.
Thanks,

{{ config('app.name') }}

@endcomponent