<?php
    use Illuminate\Support\Str;
?>   
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
                    </div>
                </div>
                <div class="row p-t-20">
                    <h3 class="card-title">Office Timings</h3>
                    <hr>
                    @foreach($values as $key => $value)
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong> {{ Str::title($key) }} </strong>
                            {{ $value }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>