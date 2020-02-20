@extends('layout')

@section('content')
<div class="d-sm-flex align-items-center pt-3 justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mg-b-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home"></i>{{ __(' Home') }}</a></li>
                @guest
                    @else
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}"><i class="fa fa-th-large"></i>{{ __(' Dashboard') }}</a></li>
                @endguest
                <li class="breadcrumb-item active" aria-current="page">{{ __('Add contest') }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-none d-md-block">
    </div>
</div>
<div class="row row-xs">
    <div class="col-sm-12 bg-white p-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('success'))
            <div role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
                <div class="toast-header">
                  <img src="..." class="rounded mr-2" alt="...">
                  <strong class="mr-auto">Bootstrap</strong>
                  <small>11 mins ago</small>
                  <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="toast-body">
                  {{ session('success') }}
                </div>
            </div>
        @endif
        <form action="{{ route('add-contest') }}" method="post">
            @csrf
            <div class="form-group">
                <div class="d-flex justify-content-center mb-3">
                    <div class="avatar avatar-xxl bordered">
                        <img src="https://fiverr-res.cloudinary.com/images/t_main1,q_auto,f_auto/gigs/15535839/original/e71daeb2a7bb11198ed957466ab6e088f341c387/create-pixel-art-for-you.png" class="rounded" alt="">
                    </div>
                </div>
                <div class="upload-text m-auto wd-250">
                    <p class="tx-center"><strong>Upload Contest image</strong></p>
                    <input type="file" name="contest_image" class="upload-input">
                </div>
            </div>
            <div class="form-group">
                <label for="name">CONTEST NAME</label>
                <input type="text" name="name" class="form-control phone-inputs @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contest name" autocomplete="off">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">DESCRIPTION</label>
                <textarea name="description" class="form-control phone-inputs @error('description') is-invalid @enderror" id="" placeholder="Say something..." autocomplete="off">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="name">START DATE</label>
                    <input type="text" id="dateFrom" name="start_date" class="form-control phone-inputs @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" placeholder="start date" autocomplete="off">
                    @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="name">END DATE</label>
                    <input type="text" id="dateTo" name="end_date" class="form-control phone-inputs @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" placeholder="end date" autocomplete="off">
                    @error('send_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-cancel btn-block">SAVE</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/home-script.js') }}"></script>
@endsection
