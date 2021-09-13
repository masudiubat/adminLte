@component('mail::message')
# {{ $details['title'] }}

Your access password is {{ $details['password'] }}

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent