<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Showcase Footloose tickets</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <h1>Footloose Showcase Tickets</h1>
    <p>Purchase here your tickets for the footloose showcase. </p>
    <div class="card">
        <form method="POST" action="/api/reservations" class="card-body">
            @csrf
            <h4>Peronal details:</h4>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                       value="{{ old('name')  }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" class="form-control" name="email" placeholder="Enter your email"
                       value="{{ old('email') }}"
                       required>
            </div>
            <h4>Tickets:</h4>
            @foreach($tickets as $ticket)
                <div class="card" style="margin-bottom: 1em;">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ticket-{{ $ticket->id }}">{{ $ticket->type }} ticket</label>
                            <input type="checkbox" class="form-control-input" name="ticket-{{ $ticket->id }}"
                                   id="ticket-{{ $ticket->id }}">
                        </div>
                        <div class="form-group">
                            <label for="number-of-tickets-{{ $ticket->id }}">Number of tickets:</label>
                            <input type="number" class="form-control-input" name="ticket-{{ $ticket->id }}-number"
                                   id="number-of-tickets-{{ $ticket->id }}">
                        </div>
                    </div>
                </div>
            @endforeach

            <button class="btn btn-danger" type="submit" style="margin-top: 2em">Submit</button>
        </form>
    </div>
</div>
</body>
</html>
