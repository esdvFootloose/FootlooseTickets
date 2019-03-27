@extends('layout')
@section('content')
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

    @if ($errors->any())
        <div class="alert alert-danger" role="alert" style="margin-top: 1em">
            Please make sure:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection