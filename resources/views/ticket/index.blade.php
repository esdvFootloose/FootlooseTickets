@extends('layouts.app')
@section('content')
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Ticket ID</th>
                <th scope="col">Type</th>
                <th scope="col">Price</th>
                <th scope="col">Time</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <th scope="row">{{ $ticket->id }}</th>
                    <td>{{ $ticket->type }}</td>
                    <td>€{{ $ticket->price }}</td>
                    <td>{{ $ticket->show_time }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection