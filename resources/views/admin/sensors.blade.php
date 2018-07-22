@extends('layout.app')
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
        @foreach($sensors as $sensor)
        <tr>
            <th scope="row">
                <a href="{{ url('/admin/sensor/' . $sensor->uuid) }}">
                {{ $sensor->owner->name }} / {{ $sensor->uuid }}
                </a>
            </td>
            <td>{{ $sensor->last_measpoint }}</td>
            <td>{{ $sensor->updated_at }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection