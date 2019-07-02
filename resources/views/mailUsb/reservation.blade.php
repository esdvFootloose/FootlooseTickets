@component('mail::message')
# New Footloose Showcase film reservation

Dear {{ $name }},

You have reserved the following {{ $amount }}x a USB stick with the Footloose infinity movie.

By clicking on the following link, you can safely pay via IDeal:
{{ $payment_url }}

Kind regards,<br>
The Footloose showcase committee
@endcomponent