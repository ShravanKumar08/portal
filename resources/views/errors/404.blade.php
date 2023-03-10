@extends('layouts.error')

@section('content')
    @component('components.errors')
        @slot('code')
            404
        @endslot

        @slot('message')
            Page not found!
        @endslot
    @endcomponent
@endsection
