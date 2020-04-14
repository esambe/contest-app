@extends('layout')

@section('content')
    <div class="d-flex align-items-center justify-content-center mt-auto">
        <div class="spinner-grow text-white wd-300 ht-300" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
       window.location.href = "";
    </script>
@endsection
