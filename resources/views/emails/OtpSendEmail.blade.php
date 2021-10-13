@component('mail::message')


Your OTP: {{ $otp['value'] }}

If you did not send this code, no further action is required.
Thanks,

{{ config('app.name') }}

@endcomponent