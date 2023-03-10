@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- <div class="form-body col-md-12">
                        <div class="row justify-content-md-center">
                            <div class="col-2 text-center">
                                <div class="form-group">
                                    {{ Form::select('year', $years, '', ['class' => 'form-control', 'id' => 'hyear', 'style' => 'width: 80px']) }}
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="table-responsive dataTable">
                        {!! $dataTable->table(['class' => 'display nowrap table table-hover table-striped table-bordered', 'id' => 'datatable-buttons']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('layouts.partials.datatable_scripts')

@push('stylesheets')    
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

@endpush

<!-- @push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable-buttons').DataTable().columns(0).search($('#hyear').val()).draw();
            $("#hyear").on("change", function () {
                $('#datatable-buttons').DataTable().columns(0).search(this.value).draw();
            });
        });
    </script>
@endpush -->
