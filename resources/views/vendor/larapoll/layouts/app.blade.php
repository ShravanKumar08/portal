@php
    $auth_employee = \Auth::user()->employee ?? new \App\Models\Employee();
    $auth_employee->appendCustomFields();
@endphp
@include('layouts.master')
