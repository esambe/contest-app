@extends('layout')

@section('styles')
    <style>
        .banner {
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.3)),
            url('{{ asset('/uploads/'.$contest->img) }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
         }
    </style>
@endsection

@section('hero')
    <div class="jumbotron banner">
        <h4 class="mg-b-0 tx-spacing--1 header-text text-white">Welcome to - <span class="secondary-text">{{ $contest->name }}</span></h4>
        <p class="lead text-white">{{ $contest->description }}</p>
        <hr class="my-4">
        <p class="lead">
            @if ($contestants->count() != 0)
                <button class="btn btn-cancel" data-toggle="modal" data-target="#ranking">RANKING</button>
            @else
                <button class="btn btn-cancel" disabled="disabled">RANKING</button>
            @endif
        </p>
        <div class="d-flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mg-b-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home"></i>{{ __(' Home') }}</a></li>
                    @guest
                        @else
                        @role('super-admin')
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}"><i class="fa fa-th-large"></i>{{ __(' Dashboard') }}</a></li>
                        @endrole
                    @endguest
                    <li class="breadcrumb-item active" aria-current="page">{{ $contest->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('success') }}
    </div>
@endif

@if (session('danger'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('danger') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Ranking -->
<div class="modal fade" id="ranking" tabindex="-1" data-backdrop="static" data-keyboard="false"  role="dialog" aria-labelledby="rankingTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title header-text">Ranking Statistics</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($contestants as $contestant)
                    <div class="pb-3 row">
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="tx-15 mr-3">
                                {{ $contestant->id }}
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs mr-2"><img src="https://www.w3schools.com/howto/img_avatar2.png" class="rounded-circle" alt=""></div>
                                <span class="secondary-text">{{ $contestant->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <button type="button" class="btn btn-cancel">
                                This contestant currently has <span class="badge badge-danger tx-16">{{  $contestant->votes->last()->vote_count ?? '0'  }}</span> votes
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="row row-xs bg-gray">
    @foreach ($contestants as $contestant)
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-lg mb-3">
                <div class="avatar avatar-xxl card-avatar">
                    {{--  <span class="avatar-initial rounded-circle">df</span>  --}}
                    <img src="{{ $contestant->user_img ? asset('/thumbnail/'.$contestant->user_img) : 'https://www.gravatar.com/avatar/EMAIL_MD5?d=https%3A%2F%2Fui-avatars.com%2Fapi%2F/Lasse+Rafn/128' }}" class="rounded-circle avatar-img" alt="">
                </div>
                <div class="card-body">
                    <p class="tx-16 tx-bold text-center secondary-text tx-22">{{ $contestant->name}}</p>
                    <p class="tx-15 text-center tx-gray-500"><i class="fa fa-map-marker"></i> {{ $contestant->city }}</p>
                    <p class="tx-15 text-center">{{ $contestant->votes->last()->vote_count ?? '0' }} Votes</p>
                    <div class="card-footer">
                        <div class="">
                            <div class="btn-group btn-block">
                                <button data-toggle="modal" data-target="#detail{{ $contestant->id }}" aria-expanded="false" class="btn btn-secondary">Detail</button>
                                <button data-toggle="modal" data-target="#payment{{ $contestant->id }}" aria-expanded="false" class="btn btn-vote">Vote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal to vote -->
                <div class="modal fade" id="payment{{ $contestant->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="paymentTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <img src="{{ asset('assets/imgs/mtn_orange.jpg') }}" alt="mtn-orange" height="50" width="100">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <div class="text-center"><span class="tx-20">This operation will request a charge of </span> <h1 class="text-center">{{ $contest->voter_charge }} FCFA</h1></div>
                                    <p class="text-center tx-16">Continue with:</p>
                                </div>
                                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="mtn-tab" data-toggle="tab" href="#mtn{{ $contestant->id }}" role="tab" aria-controls="mtn" aria-selected="true">MTN</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="orange-tab" data-toggle="tab" href="#orange{{ $contestant->id }}" role="tab" aria-controls="orange" aria-selected="false">ORANGE</a>
                                    </li>
                                </ul>
                                <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                                    <div class="tab-pane fade show active" id="mtn{{ $contestant->id }}" role="tabpanel" aria-labelledby="mtn-tab">
                                        <div>
                                            <form action="{{ route('vote') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="contest_id" value="{{ $contest->id}}">
                                                <input type="hidden" name="contestant_id" value="{{ $contestant->id}}">
                                                <input type="hidden" name="payment_method" value="mtn">
                                                <div class="input-group mg-b-10">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">+237</span>
                                                    </div>
                                                    <input type="text" class="form-control phone-inputs" placeholder="Enter MTN MoMo number" name="number"
                                                    onkeypress="
                                                        if(event.which &lt; 48 || event.which &gt; 57 )
                                                        if(event.which != 8) return false;
                                                        if(event.which === 32) return false;

                                                    "
                                                    autocomplete="off"
                                                    max="9"
                                                    >
                                                </div>
                                                <button class="btn btn-cancel btn-block">PROCEED</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="orange{{ $contestant->id }}" role="tabpanel" aria-labelledby="orange-tab">
                                        @guest
                                            <a class="btn btn-cancel btn-block" href="#register{{ $contestant->id }}" data-toggle="modal">{{ __('Get started') }}</a>
                                            @else
                                            <form action="{{ route('vote') }}" method="post" >
                                                @csrf
                                                <input type="hidden" name="contest_id" value="{{ $contest->id}}">
                                                <input type="hidden" name="contestant_id" value="{{ $contestant->id}}">
                                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                <input type="hidden" name="payment_method" value="orange">
                                                <button class="btn btn-cancel btn-block">PROCEED</button>
                                            </form>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal to view more detail -->
                    <div class="modal fade" id="detail{{ $contestant->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="paymentTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center pb-3">
                                        <div class="avatar avatar-lg">
                                            <img src="{{ $contestant->user_img ? asset('/thumbnail/'.$contestant->user_img) : 'https://www.gravatar.com/avatar/EMAIL_MD5?d=https%3A%2F%2Fui-avatars.com%2Fapi%2F/Lasse+Rafn/128' }}" class="rounded-circle avatar-img" alt="">
                                        </div>
                                        <h2 class="secondary-text pl-3">{{ $contestant->name }}</h2>
                                    </div>
                                    <p>
                                        {{ $contestant->description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @guest
                        <div class="modal fade" id="register{{ $contestant->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="paymentTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3></h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home{{ $contestant->id  }}" role="tab" aria-controls="pills-home" aria-selected="true">Signin</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile{{ $contestant->id  }}" role="tab" aria-controls="pills-profile" aria-selected="false">Signup</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent{{ $contestant->id  }}">
                                            <div class="tab-pane fade show active" id="pills-home{{ $contestant->id  }}" role="tabpanel" aria-labelledby="pills-home-tab">
                                                <span> Already have an account here ? </span>
                                                <form method="POST" action="{{ route('login') }}">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                                        <div class="col-md-6">
                                                            <input id="email" type="email" class="form-control phone-inputs @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                                        <div class="col-md-6">
                                                            <input id="password" type="password" class="form-control phone-inputs @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-8 offset-md-4">
                                                            <button type="submit" class="btn btn-cancel">
                                                                {{ __('Sign in') }}
                                                            </button>

                                                            @if (Route::has('password.request'))
                                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                                    {{ __('Forgot Your Password?') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="pills-profile{{ $contestant->id  }}" role="tabpanel" aria-labelledby="pills-profile-tab">
                                                <form method="POST" action="{{ route('register') }}">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                                        <div class="col-md-6">
                                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-6 offset-md-4">
                                                            <button type="submit" class="btn btn-primary">
                                                                {{ __('Set account') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endguest
            </div>
        </div>
    @endforeach
</div>
<div>
    {{ $contestants->links() }}
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/home-script.js') }}"></script>
@endsection
