@extends('layout.app')
@section('title')
    {{ __('Organization:') }} {{ $organization->name }} - city_matters
@endsection
@section('content')
    <div>
        <h1>
            {{ $organization->name }} <small>({{ $organization->slug }})</small>
        </h1>
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
        </tbody>
    </table>


    <h2>{{ __('Members') }}</h2>

    <div class="clearfix">
        <a class="btn btn-primary float-right p-2" href="{{ route('admin.organizations.members.add') }}" role="button"><i class="fa fa-plus"></i> Add new member</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Admin') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
            @foreach($organization->members as $member)
                <tr>
                    <td>{{ $member->name}}</td>
                    <td>{{ $member->pivot->is_admin }}</td>
                    <td>
                        <a href="#"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection