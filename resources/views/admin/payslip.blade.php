@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body" id="print_content">
                    Test
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <iframe class="preview-pane" type="application/pdf" width="100%" height="650" frameborder="0" style="position:relative;z-index:999"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/plugins/jsPDF/dist/jspdf.debug.js') }}"></script>
    <script src="{{ asset('assets/plugins/jsPDF/examples/js/html2canvas.js') }}"></script>
    <script src="{{ asset('assets/plugins/jsPDF/examples/js/editor.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var doc = new jsPDF();

// We'll make our own renderer to skip this editor
            var specialElementHandlers = {
                '#editor': function(element, renderer){
                    return true;
                }
            };

// All units are in the set measurement for the document
// This can be changed to "pt" (points), "mm" (Default), "cm", "in"
            doc.fromHTML($('#print_content').get(0), 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });

        });
    </script>
@endpush