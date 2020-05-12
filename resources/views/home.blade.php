@extends('layout')

@section('content')
{{-- <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>

    </div>
    <div class="d-none d-md-block">
    </div>
</div> --}}
<div class="mt-3">
    <h4 class="mg-b-0 tx-spacing--1 text-white header-text">Marketplaz - Contests</h4>
    <p class="text-white tx-20 secondary-text">
        Below is a list of contest which are currently active. Visit one of them to vote.
    </p>
</div>
<div class="row row-xs">
    @foreach ($contests as $contest)
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-lg mb-3">
                <div class="wrapper">
                    <img src="{{ $contest->img ? asset('/uploads/'.$contest->img) : 'https://i.ytimg.com/vi/6BiqdXF9wNw/maxresdefault.jpg' }}" class="card-img-top" alt="">
                </div>
                <div class="card-body">
                    <p class="tx-16 tx-bold text-center tx-gray-500 secondary-text-">{{ $contest->name }}</p>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <a href="{{ $contest->singlePath() }}" class="btn btn-cancel btn-block">GOTO CONTEST</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div>
    {{ $contests->links() }}
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/home-script.js') }}"></script>
@endsection
