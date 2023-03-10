@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($Model, ['url' => [ "admin/employee/access_store", $Model->id],'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::hidden('name', implode(',', $getallpermissions)) }}
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">

                                <div id="treeview">
                                  <ul>
                                   <li>Select All
                                    <ul>
                                        @foreach ($Permissions as $parent => $Permission)
                                            <li data-jstree='{ "selected" : false }'>
                                                {{ $parent }}
                                                <ul>
                                                    @foreach ($Permission as $value)
                                                        @php
                                                            $name = "name[{$value->id}]";
                                                        @endphp
                                                        <li data-jstree='{{ in_array($value->name, $getallpermissions) ? '{ "selected" : true }' : ""}}'>
                                                            {{ Form::label($name, $value->name) }} <br/>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                  </li>
                                 </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/jstree/dist/themes/default/style.min.css') }}" />
<script type="text/javascript" src="{{ asset('assets/plugins/jstree/dist/jstree.min.js') }}"></script>
<script type="text/javascript">
    $(function ($) {
        $('#treeview').jstree({
            "plugins": ["checkbox"],
            core: {
                "themes": {
                    "icons": false
                }
            }
        });
        $('#treeview').on('changed.jstree', function (e, data) {
            var i, j, r = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                $elem = data.instance.get_node(data.selected[i]).text;
                
                var label = $($elem);
                
                if(typeof label.html() != 'undefined'){
                    r.push(label.html());
                }
            }
            $('input[name="name"]').val(r.join(','));
        });
    });
</script>
@endpush