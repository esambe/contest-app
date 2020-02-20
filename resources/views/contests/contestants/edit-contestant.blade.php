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
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit ') . $contestant->name }}</li>
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
                    <span aria-hidden="true">×</span>
                </button>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('update-contestant', $contestant->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="d-flex justify-content-center mb-3">
                    <div class="avatar avatar-xxl bordered">
                        <img id="target" src="{{ $contestant->user_img ? asset('/thumbnail/'.$contestant->user_img) :  'https://www.gravatar.com/avatar/EMAIL_MD5?d=https%3A%2F%2Fui-avatars.com%2Fapi%2F/Lasse+Rafn/128'}}" class="rounded" alt="">
                    </div>
                </div>
                <div class="upload-text m-auto wd-250">
                    <p class="tx-center"><strong>Upload Contest image</strong></p>
                    <input type="file" name="user_img" class="upload-input" id="file-picker">
                </div>
            </div>

            <input type="hidden" name="contest_id" value="{{ $contestant->id }}">
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="name">FULL NAME</label>
                    <input type="text" name="name" class="form-control phone-inputs @error('name') is-invalid @enderror" value="{{ $contestant->name }}" placeholder="Contest name" autocomplete="off">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="email">EMAIL</label>
                    <input type="text" name="email" class="form-control phone-inputs @error('email') is-invalid @enderror" value="{{ $contestant->email }}" placeholder="Email" autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="phone">PHONE</label>
                    <input type="text" name="phone" class="form-control phone-inputs @error('phone') is-invalid @enderror" value="{{ $contestant->phone }}" placeholder="Phone number" autocomplete="off">
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="city">CITY</label>
                    <input type="text" name="city" class="form-control phone-inputs @error('city') is-invalid @enderror" value="{{ $contestant->city }}" placeholder="City" autocomplete="off">
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="description">DESCRIPTION</label>
                <textarea name="description" class="form-control phone-inputs @error('description') is-invalid @enderror" id="" placeholder="Say something..." autocomplete="off">{{ $contestant->description }}</textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
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
