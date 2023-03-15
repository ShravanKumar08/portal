<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row p-t-20">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> Name: </strong>
                            {{ $Model->name }}
                        </div>
                        <div class="form-group">
                            <strong> Team Lead: </strong>
                            {{ @$Model->lead->user->name }}
                        </div>
                        <div class="form-group">
                            <strong> Team Members: </strong>
                            {{ implode(' ,', $checkedTeamMates) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>