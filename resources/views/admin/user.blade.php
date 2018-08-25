@extends('layout.app')
@section('title')
    {{ __('Users') }} - city_matters
@endsection
@section('content')
    <h1>
        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=48" />
        {{ $user->name }}
        <small>({{ $user->email }})</small>
    </h1>
    <p>
        Actions:
        <a href="#"><i class="fa fa-pencil"></i></a>
        <a href="#"><i class="fa fa-trash"></i></a>
    </p>
    <h2>{{ __('Sensors') }} ({{ count($user->sensors) }})</h2>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col">Identifier</th>
            <th scope="col">Last measpoint</th>
            <th scope="col">Last changed</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user->sensors as $sensor)
            <tr>
                <td scope="row">
                    <a href="{{ url('/admin/sensor/' . $sensor->uuid) }}">
                        {{ $sensor->owner->name }} / <strong>{{ $sensor->uuid }}</strong>
                    </a>
                </td>
                <td>{{ $sensor->last_measpoint }}</td>
                <td>{{ $sensor->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection