@component('mail::message')
# New Footloose Showcase ticket reservation

Dear {{ $name }},

You have reserved the following tickets for the showcase:
{{ $tickets }}

You will soon receive payment details to finalize your reservation.

Kind regards,<br>
The Footloose showcase committee
@endcomponent
