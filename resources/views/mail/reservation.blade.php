@component('mail::message')
# New Footloose Showcase ticket reservation

Dear {{ $name }},

You have reserved the following tickets for the showcase:
{{ $tickets }}

By clicking on the following link, you can safely pay for your tickets via IDeal:
{{ $payment_url }}

After we receive confirmation of your payment, we will send you your tickets.

Kind regards,<br>
The Footloose showcase committee
@endcomponent
