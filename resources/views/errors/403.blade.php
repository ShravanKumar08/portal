@extends('layouts.error')

@section('content')
    @component('components.errors')
        @slot('code')
            403
        @endslot

        @slot('message')
            {{ @$exception->getMessage() }}
        @endslot
    @endcomponent
@endsection