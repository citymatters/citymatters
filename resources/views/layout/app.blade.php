@extends('layout.base')

@section('content')
    No content specified.
@endsection

@section('body')
@include('layout.components.navbar')
@auth
    @include('layout.components.adminbar')
@endauth
<div class="container">
    <div id="content" class="mt-5">
        @yield('content')
    </div>
</div>
@endsection