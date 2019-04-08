@extends('layouts.app')
@section('content')
    <div class="container">
        <a href="/reservations/download" class="btn btn-danger" style="margin-bottom: 1.5em; float: right">Download</a>

            <table class="table">
            <thead>
            <tr>
                <th scope="col">Ticket ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Type</th>
                <th scope="col">Time</th>
                <th scope="col">Amount</th>
                <th scope="col">Paid</th>
                <th scope="col">Updated at</th>

            </tr>
            </thead>
            <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <th scope="row">{{ $reservation->id }}</th>
                    <td>{{ $reservation->name }}</td>
                    <td>{{ $reservation->email }}</td>
                    <td>{{ $reservation->type }}</td>
                    <td>{{ $reservation->show_time }}</td>
                    <td>{{ $reservation->amount }}</td>
                    <td>{{ $reservation->paid ? 'yes' : 'no'}}</td>
                    <td>{{ $reservation->updated_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection