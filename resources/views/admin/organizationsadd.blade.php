@extends('layout.app')
@section('title')
    {{ __('Add organization') }} - city_matters
@endsection
@section('content')
    <form action="{{ route('admin.organizations.add') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="organizationName">{{ __('Organization name') }}</label>
            <input type="text" class="form-control" id="code" name="organizationName" aria-describedby="organizationNameHelp" placeholder="{{ __('e.g. city_matters') }}">
            <small id="organizationNameHelp" class="form-text text-muted">{{ __('The name of the organization.') }}</small>
        </div>
        <div class="form-group">
            <label for="organizationSlug">{{ __('Organization slug') }}</label>
            <input type="text" class="form-control" id="code" name="organizationSlug" aria-describedby="organizationSlugHelp" placeholder="{{ __('e.g. ctymttrs or city_matters') }}">
            <small id="organizationSlugHelp" class="form-text text-muted">{{ __('The slug of the organization (used for URLs).') }}</small>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </form>
@endsection