<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="timeline" style="font-size: 14px;">
                        @forelse($datas as $data)
                        <li <?php echo $loop->iteration % 2 ? "" : "class='timeline-inverted'"?>>
                            <div class="timeline-badge">
                                <img class="img-responsive" alt="user" src="{{ @$data->user->employee ? @$data->user->employee->avatar : $logo_light_icon }}" alt="img">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4 class="timeline-title">{{ @$data->user->name }}</h4>
                                    <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ ucfirst($data->event) }} at {{ Carbon\Carbon::parse($data->created_at)->diffForHumans() }} </small> </p>
                                </div>
                                <div class="timeline-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Column</td>
                                            <td>Old</td>
                                            <td>New</td>
                                        </tr>
                                        @foreach ($data['new_values'] as $key => $new_value)
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td>{{ @$data['old_values'][$key] ?? '-' }}</td>
                                                <td>{{ $new_value }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </li>
                        @empty
                            <li>
                                <div class="timeline-badge danger"><i class="fal fa-frown"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-body">
                                        <p>No Records Found</p>
                                    </div>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
