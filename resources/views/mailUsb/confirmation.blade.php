@component('mail::message')
# Your Showcase Infinity film order

Dear {{ $name }}

You have paid your order for {{ $amount }}x a USB stick with the Footloose infinity movie.

You can pick up your order at the showcase movie night on September 4th. If you won't be there, please send us an email at <showcase@esdvfootloose.nl> to arrange a pickup date.

Kind regards,<br>
The showcase committee<br>
@endcomponent
