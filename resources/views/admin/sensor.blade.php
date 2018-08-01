@extends('layout.app')
@section('title')
    {{ $sensor->owner->name }}/{{ $sensor->uuid }}  - city_matters
@endsection
@section('content')
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col">Identifier</th>
            <th scope="col">Last measpoint</th>
            <th scope="col">Last changed</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">
                {{ $sensor->owner->name }} / {{ $sensor->uuid }}
            </td>
            <td>{{ $sensor->last_measpoint }}</td>
            <td>{{ $sensor->updated_at }}</td>
        </tr>
        </tbody>
    </table>
@endsection