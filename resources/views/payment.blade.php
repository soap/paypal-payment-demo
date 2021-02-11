@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ url('charge') }}" method="post">
            <input type="text" name="amount" />
            {{ csrf_field() }}
            <input type="submit" name="submit" value="Pay Now">
        </form>
    </div>
@endsection('content')