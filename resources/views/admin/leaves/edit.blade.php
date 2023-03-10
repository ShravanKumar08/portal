@extends('layouts.master')

@section('content')
    @include('admin.leaves.partials.form')
@endsection

@push('scripts')
    @include('layouts.partials.leaveform_scripts')
@endpush