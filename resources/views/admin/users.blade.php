@extends('layout.app')
@section('title')
    {{ __('Users') }} - city_matters
@endsection
@section('content')
    <h1>{{ __('Users') }}</h1>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('E-Mail') }}</th>
            <th scope="col">{{ __('Sensors') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td><a href="{{ route('admin.user', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ count($user->sensors) }}</td>
                <td>
                    <a href="#"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection