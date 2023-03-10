@if($Model->name == 'THEME_COLOR')
    @include('layouts.partials.theme_color')
@elseif($Model->name == 'GITHUB_CREDENTIALS')
    @include('employee.usersettings.partials._github_credentials')
@else
    <div class="form-body">
        <div class="row p-t-20">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('value', 'Value *', ['class' => '']) }}
                    @if($Model->fieldtype == 'multiselect')
                        {{ Form::select('value[]', $selectvalues, old('value'), ['class' => 'form-control searchablemultiselect', 'multiple' => true]) }}
                    @elseif($Model->fieldtype == 'file')
                        <input type="file" class="dropify" name="value" data-height="350"
                               @if($Model->value)data-default-file="{{ $Model->value }}"@endif/>
                    @elseif($Model->fieldtype == 'textarea')
                        @include('admin.settings.partials._textarea')
                    @else
                        {{ Form::text('value', old('value'), ['class' => 'form-control']) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
<div class="form-actions">
    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
    <button type="reset" class="btn btn-inverse">Reset</button>
</div>

@if($Model->fieldtype == 'file')
    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/dist/css/dropify.min.css') }}">

        <script src="{{ asset('assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.dropify').dropify();
            });
        </script>
    @endpush

@elseif($Model->fieldtype == 'multiselect')
    @include('layouts.partials.multiselect_scripts')
@endif
