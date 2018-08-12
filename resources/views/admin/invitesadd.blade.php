@extends('layout.app')
@section('title')
    {{ __('Add invite code') }} - city_matters
@endsection
@section('content')
    <form action="{{ route('admin.invites.add') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">{{ __('Invite code') }}</label>
            <input type="text" class="form-control" id="code" name="code" aria-describedby="codeHelp" placeholder="{{ __('Enter code') }}">
            <small id="codeHelp" class="form-text text-muted">{{ __('The code users will be able to use to sign up.') }}</small>
        </div>
        <input type="hidden" name="forever" value="true" checked>
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </form>
@endsection