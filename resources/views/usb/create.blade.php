@extends('layout')
@section('content')
    <h1>Footloose Showcase Movie</h1>
    <p>Purchase here your usb stick with the footloose showcase movie. </p>

    @if ($errors->any())
        <div class="alert alert-danger mt--1m" role="alert">
            Please make sure:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border--dark-blue">
        <form method="POST" action="/api/reservations/film" class="card-body">
            @csrf
            <h4>Personal details:</h4>
            <div class="form-group">
                <label for="name">Full name:</label>
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
            <p>Please note that the transaction costs of €0,25 per order are not included in the price</p>
            <div class="card border--light-blue mb--1m">
                <div class="card-body">
                    <h5>USB stick with the movie</h5>
                    <div class="form-group">
                        <label for="amount">Number of usb sticks:</label>
                        <input type="number" class="form-control-input" name="amount"
                               id="amount"> x €2
                    </div>
                </div>
            </div>
            <button class="btn button--pink mt--2m" type="submit">Submit</button>
            <div class="text--center">
                <img src="img/logo.png" class="logo">
            </div>
        </form>

    </div>


@endsection