<div class="form-group">
    {!! Form::label('subject', 'Subject') !!}
    {{ Form::hidden('toemployee',@$request->toemployee) }}

    {{ Form::text('subject', $contents['PAYSLIP_CONTENT_MAIL']['subject'], ['class' => 'form-control', 'placeholder' => 'Subject', 'autocomplete' => 'off', 'autofocus' => true]) }}
</div>
<div class="form-group">
    {!! Form::label('mail_content', 'Mail Content') !!}
    {{ Form::textarea('mail_content', $contents['PAYSLIP_CONTENT_MAIL']['content'], ['class' => 'form-control textarea_editor','rows'=> 15]) }}
</div>
<div class="form-group">
    {!! Form::label('pdf_content', 'PDF Content') !!}
    {{ Form::textarea('pdf_content', $contents['PAYSLIP_PDF_MAIL']['content'], ['class' => 'form-control textarea_editor', 'rows'=> 15]) }}
</div>
