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
                <li class="breadcrumb-item active" aria-current="page">{{ $contest->name }}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Contestants') }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-none d-md-block">
        <a href="#add" data-toggle="modal" class="btn btn-cancel">{{ __('ADD CONTESTANTS') }}</a>
    </div>
</div>

<div data-label="Example" class="row">
    <div class="col-sm-12 bg-white p-5">
        @if (session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ session('success') }}
            </div>
        @endif
        @if (session('danger'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ session('danger') }}
            </div>
        @endif
        @if ($errors->all())
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                @foreach ($errors->all() as $message)
                   <p>{{ $message }}</p>
                @endforeach
            </div>
        @endif
        <table id="contest" class="table">
            <thead>
                <th class="wp-10">{{ __('#ID') }}</th>
                <th class="wp-20">{{ __('NAME') }}</th>
                <th class="wp-10">{{ __('EMAIL') }}</th>
                <th class="wp-10">{{ __('PHONE') }}</th>
                <th class="wp-5">{{ __('CITY') }}</th>
                <th class="wp-5">{{ __('ACTION') }}</th>
            </thead>
            <tbody>
                @foreach ($contest->contestants as $contestant)
                    <tr>
                        <td>{{ $contestant->id }}</td>
                        <td>{{ $contestant->name }}</td>
                        <td>{{ $contestant->email }}</td>
                        <td>{{ $contestant->phone }}</td>
                        <td>{{ $contestant->city }}</td>
                        <td class="d-flex">
                            <a href="{{ route('edit-contestant', $contestant->id) }}" class="btn btn-outline text-primary"><i class="fa fa-pencil"> Edit</i></a>
                            <a href="{{ route('delete-contestant', $contestant->id) }}" class="btn btn-outline text-danger"><i class="fa fa-trash"> Delete</i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <!-- Modal -->
        <div class="modal fade" id="add" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('add-contestant') }}" method="post">
                            @csrf
                            <div class="form-group d-flex justify-content-center">
                                <div class="avatar avatar-xxl">
                                    <img src="https://www.w3schools.com/howto/img_avatar2.png" class="rounded-circle" alt="">
                                </div>
                            </div>
                            <h6 class="text-center mb-3">Upload Image</h6>

                            <input type="hidden" name="contest_id" value="{{ $contest->id }}">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="name">FULL NAME</label>
                                    <input type="text" name="name" class="form-control phone-inputs @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contest name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email">EMAIL</label>
                                    <input type="text" name="email" class="form-control phone-inputs @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email">
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
                                    <input type="text" name="phone" class="form-control phone-inputs @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Phone number">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="city">CITY</label>
                                    <input type="text" name="city" class="form-control phone-inputs @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="City">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">DESCRIPTION</label>
                                <textarea name="description" class="form-control phone-inputs @error('description') is-invalid @enderror" id="" placeholder="Say something...">{{ old('description') }}</textarea>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/home-script.js') }}"></script>
@endsection
