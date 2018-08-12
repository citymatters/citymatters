@extends('layout.app')
@section('title')
    {{ __('Organizations') }} - city_matters
@endsection
@section('content')
    <div class="clearfix">
        <a class="btn btn-primary float-right p-2" href="{{ route('admin.organizations.add') }}" role="button"><i class="fa fa-plus"></i> Add new</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col">{{ __('ID') }}</th>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Slug') }}</th>
            <th scope="col">{{ __('Members') }}</th>
            <th scope="col">{{ __('Sensors') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($organizations as $organization)
            <tr>
                <td>{{ $organization->id }}</td>
                <td><a href="{{ route('admin.organization', ['id' => $organization->id]) }}">{{ $organization->name }}</a></td>
                <td>{{ $organization->slug }}</td>
                <td>{{ count($organization->sensors) }}</td>
                <td>{{ count($organization->members) }}</td>
                <td>
                    <a href="#"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection