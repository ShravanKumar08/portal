@extends('layouts.error')

@section('content')
    @component('components.errors')
        @slot('code')
            419
        @endslot

        @slot('message')
            The page has expired due to inactivity
        @endslot
    @endcomponent
@endsection