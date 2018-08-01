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
        <div class="col-md-6 col-sm-12">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="foreverCheckbox" name="forever" value="true" checked>
                <label class="form-check-label" for="foreverCheckbox">{{ __('Valid forever') }}</label>
                <small id="codeHelp" class="form-text text-muted">{{ __('If you check this, the invite code will be valid forever.') }}</small>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </form>
@endsection