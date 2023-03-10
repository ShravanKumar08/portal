{{ Form::open(['route' => 'question.download']) }}
<div class="card">
    <div class="card-body">
        <div class="form-body">
            <h3 class="card-title">Generated Q/A
                @if($questions)
                <button class="btn btn-sm btn-primary pull-right">Download</button>
                @endif
            </h3>
            <hr>
            <div class="col-12">
                @foreach($questions as $question)
                    {{ Form::hidden('question[]', $question->id) }}
                    <p>{{ $loop->iteration }}) {{ $question->name }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
