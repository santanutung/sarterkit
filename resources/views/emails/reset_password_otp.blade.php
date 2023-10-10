@component('mail::message')
    Your otp is {{ $otp }}


    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
