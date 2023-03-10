<div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body" style="background: #ccd5d9;">
                <center class="m-t-30"> 
                    @if($candidate->gender =="M" )
                    <img src="{{ asset('assets/images/male.png') }}" class="img-circle" width="197">
                    @else
                    <img src="{{ asset('assets/images/female.png') }}" class="img-circle" width="197">
                    @endif
                    <h6 class="card-subtitle">{{$candidate->name}}</h6>
                         <p class="ont-medium m-t-10">{{$designation->name}}</p>
                </center>
            </div>
                {{-- <div><hr> </div>
            <div class="card-body">                     
                {{ Form::model($model,['url' => [ "admin/callstatus", $model->id],'id'=>'interveiwcall_post','method' => 'post', 'class' => 'form-horizontal']) }}
                @include('admin.interviewcall.partials.show.From')
                {{ Form::close() }}
            </div> --}}
         </div>
    </div>
