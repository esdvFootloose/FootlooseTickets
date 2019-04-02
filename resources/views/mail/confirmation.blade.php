@component('mail::message')
# Your tickets for the showcase

Dear {{ $name }}

You have reserved the following tickets for the showcase:
{{ $tickets }}


Please print this mail and bring it to the performance on the 10th of June to the Parktheater:<br>
Elzentlaan 50<br>
5615 CN Eindhoven<br>


We will see you at the showcase,<br>
Kind regards,<br>
The showcase committee<br>



This ticket has been verified on {{ $paid_time }}
@endcomponent
