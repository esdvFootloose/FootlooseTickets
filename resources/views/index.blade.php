<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Showcase Footloose tickets</title>
</head>
<body>
<form method="POST" action="/api/reservations">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
    </div>
    @foreach($tickets as $ticket)
        <div>
            <label for="ticket{{ $ticket->id }}">{{ $ticket->type }}</label>
            <input type="checkbox" id="ticket{{ $ticket->id }}">
        </div>
        <div>
            <label for="number-of-tickets{{ $ticket->id }}">Number of tickets</label>
            <input type="number" id="number-of-tickets{{ $ticket->id }}">
        </div>
    @endforeach

    <button type="submit">Submit</button>
</form>
</body>
</html>
