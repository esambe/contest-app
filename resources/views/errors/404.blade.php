@extends('layout');

@section('content')
    <div class="d-flex justify-content-center align-content-center">
        <div class="container">
            <h1 class="text-center tx-white">Sorry the page you are looking for is not available!</h1>
            <p class="tx-center">
                <span class="tx-60 tx-white"><i class="fa fa-frown-o"></i></span>
            </p>
            <div class="tx-center">
                <a href="{{ url('/') }}" class="text-white tx-30"><u>Back home</u></a>
            </div>
        </div>
    </div>
@endsection
