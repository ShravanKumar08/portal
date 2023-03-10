@extends('layouts.master')

@section('content')
    @include('admin.userpermissions.partials.form')
@endsection

@push('scripts')
    @include('layouts.partials.permissionform_scripts')
@endpush