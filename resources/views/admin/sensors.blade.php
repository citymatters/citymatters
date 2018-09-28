@extends('layout.app')
@section('title')
    {{ __('Sensor list') }} - city_matters
@endsection
@section('content')
    @component('layout.components.alert')
        @slot('type')
            warning
        @endslot
        @slot('title')
            Test Alert
        @endslot
        Das ist der Testalert
    @endcomponent
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
            <td scope="row">
                <a href="{{ url('/admin/sensor/' . $sensor->uuid) }}">
                {{ $sensor->owner instanceof \App\Organization ? $sensor->owner->slug : $sensor->owner->name }} / <strong>{{ $sensor->uuid }}</strong>
                </a>
            </td>
            <td>{{ $sensor->last_measpoint }}</td>
            <td>{{ $sensor->updated_at }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $sensors->links() }}
@endsection