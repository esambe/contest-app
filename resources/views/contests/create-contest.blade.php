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
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('success') }}
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

        <form action="{{ route('add-contest') }}" method="post">
            @csrf
            <div class="form-group">
                <div class="d-flex justify-content-center mb-3">
                    <div class="avatar avatar-xxl bordered">
                        <img id="target" src="https://fiverr-res.cloudinary.com/images/t_main1,q_auto,f_auto/gigs/15535839/original/e71daeb2a7bb11198ed957466ab6e088f341c387/create-pixel-art-for-you.png" class="rounded" alt="">
                    </div>
                </div>
                <div class="upload-text m-auto wd-250">
                    <p class="tx-center"><strong>Upload Contest image</strong></p>
                    <input type="file" name="img" class="upload-input" id="file-picker">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <label for="name">CONTEST NAME</label>
                    <input type="text" name="name" class="form-control phone-inputs @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contest name" autocomplete="off">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="name">VOTER CHARGE</label>
                    <input type="text" name="voter_charge" class="form-control phone-inputs @error('voter_charge') is-invalid @enderror" value="{{ old('voter_charge') }}" placeholder="Amount"
                    autocomplete="off"
                    onkeypress="
                        if(event.which &lt; 48 || event.which &gt; 57 )
                        if(event.which != 8) return false;
                        if(event.which === 32) return false;

                    "
                    max="9"
                    >
                    @error('voter_charge')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
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
    <script>
        function showImage(src,target) {
            var fr=new FileReader();
            // when image is loaded, set the src of the image where you want to display it
            fr.onload = function(e) { target.src = this.result; };
            src.addEventListener("change",function() {
                // fill fr with image data
                fr.readAsDataURL(src.files[0]);
            });
        }

        var src = document.getElementById("file-picker");
        var target = document.getElementById("target");
        showImage(src,target)
    </script>
@endsection
