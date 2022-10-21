@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Button Text {{ $mailData['message'] }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
