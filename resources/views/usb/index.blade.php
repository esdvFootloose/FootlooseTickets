@extends('layouts.app')
@section('content')
    <div class="container">
        <a href="/reservations/movie/download" class="btn btn-danger" style="margin-bottom: 1.5em; float: right">Download</a>
        <table class="table table-responsive">
            <thead>
            <tr>
                <th scope="col">Ticket ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Amount</th>
                <th scope="col">Paid</th>
                <th scope="col">Picked up</th>
                <th scope="col">Updated at</th>
                <th scope="col">Tikkie</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <th scope="row">{{ $reservation->id }}</th>
                    <td>{{ $reservation->name }}</td>
                    <td>{{ $reservation->email }}</td>
                    <td>{{ $reservation->amount }}</td>
                    <td>{{ $reservation->paid ? 'yes' : 'no'}}</td>
                    <td>{{ $reservation->picked_up ? 'yes' : 'no'}}</td>
                    <td>{{ $reservation->updated_at }}</td>
                    <td>{{ $reservation->tikkie_link }}</td>
                    @if(!$reservation->paid && !$reservation->tikkie_link)
                    <td><a href="/reservations/movie/newTikkie/{{ $reservation->id }}" class="btn btn-danger">New</a></td>
                        @else
                        <td></td>
                    @endif
                    @if($reservation->paid && !$reservation->picked_up)
                        <td><a href="/reservations/movie/pickup/{{ $reservation->id }}" class="btn btn-danger">Pick up</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection