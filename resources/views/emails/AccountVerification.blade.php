@component('mail::message')
# {{ $verificationDetails['title'] }}

<h2>Welcome to the site {{$verificationDetails['name']}}</h2>
<br />
Your registered email-id is {{$verificationDetails['email']}} , Please click on the below link to verify your email account
<br />
<a href="{{url('user/verify', $verificationDetails['token'])}}">Verify Email</a>

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent