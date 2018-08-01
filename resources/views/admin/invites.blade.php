@extends('layout.app')
@section('title')
    {{ __('Invite codes') }} - city_matters
@endsection
@section('content')
    <div class="clearfix">
        <a class="btn btn-primary float-right p-2" href="{{ route('admin.invites.add') }}" role="button"><i class="fa fa-plus"></i> Add new</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col">{{ __('Code') }}</th>
            <th scope="col">{{ __('Valid until') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invites as $invite)
        <tr>
            <td>{{ $invite->code }}</td>
            <td>{{ $invite->valid_until }}</td>
            <td>
                <a href="{{ route('admin.invites.delete', ['id' => $invite->_id]) }}"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection