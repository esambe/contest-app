@extends('layout')

@section('content')
<div class="d-sm-flex align-items-center pt-3 justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mg-b-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home"></i>{{ __(' Home') }}</a></li>
                @role('super-admin')
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-th-large"></i>{{ __(' Dashboard') }}</li>
                @endrole
            </ol>
        </nav>
    </div>
    @role('super-admin')
        <div class="d-none d-md-block">
            <a href="{{ route('create-contest') }}" class="btn btn-cancel">{{ __('ADD COMPETETION') }}</a>
        </div>
    @endrole
</div>

<div data-label="Example" class="row">
    <div class="col-sm-12 bg-white p-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('danger'))
            <div class="alert alert-danger">
                {{ session('danger') }}
            </div>
        @endif

        <table id="contest" class="table">
            <thead>
                <th class="wp-10">{{ __('#ID') }}</th>
                <th class="wp-20">{{ __('NAME') }}</th>
                <th class="wp-10">{{ __('START DATE') }}</th>
                <th class="wp-10">{{ __('END DATE') }}</th>
                <th class="wp-5">{{ __('ACTION') }}</th>
            </thead>
            <tbody>
                @foreach ($contests as $contest)
                    <tr>
                        <td>{{ $contest->id }}</td>
                        <td>{{ $contest->name }}</td>
                        <td>{{ $contest->start_date }}</td>
                        <td>{{ $contest->end_date }}</td>
                        <td class="d-flex">
                            <a href="{{ $contest->editPath() }}" class="btn btn-outline text-primary"><i class="fa fa-pencil"> Edit</i></a>
                            <a href="{{ $contest->showPath() }}" class="btn btn-outline text-success"><i class="fa fa-eye"> Goto</i></a>
                            <a href="{{ route('delete-contest', $contest->id) }}" class="btn btn-outline text-danger"><i class="fa fa-trash"> Delete</i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/home-script.js') }}"></script>
@endsection
