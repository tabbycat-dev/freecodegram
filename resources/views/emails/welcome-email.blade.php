@component('mail::message')
Hello Welcome to freecodeGram, I am Tan Nguyen

This email is to verify your mail 
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}

Tan Nguyen
@endcomponent
