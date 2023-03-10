<html>
<head>
    <style type="text/css">
        /* The standalone checkbox square*/
        .checkbox {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            display: inline-block;
        }

        /* This is what simulates a checkmark icon */
        .checkbox.checked:after {
            content: '';
            display: block;
            width: 4px;
            height: 7px;

            /* "Center" the checkmark */
            position: relative;
            top: 4px;
            left: 7px;

            border: solid #000;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .text-center{
            text-align: center;
        }
        
        .row{
            width: 100%;
            float: left;
            margin-bottom: 5px;
        }

        .col-print-1 {width:8%;  float:left;}
        .col-print-2 {width:16%; float:left;}
        .col-print-3 {width:25%; float:left;}
        .col-print-4 {width:33%; float:left;}
        .col-print-5 {width:42%; float:left;}
        .col-print-6 {width:50%; float:left;}
        .col-print-7 {width:58%; float:left;}
        .col-print-8 {width:66%; float:left;}
        .col-print-9 {width:75%; float:left;}
        .col-print-10{width:83%; float:left;}
        .col-print-11{width:92%; float:left;}
        .col-print-12{width:100%; float:left;}

        /*@media print*/
        /*{*/
            /*.question-row{*/
                /*page-break-inside: avoid;*/
            /*}*/
        /*}*/
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-print-4">&nbsp;</div>
        <div class="col-print-4 text-center">
            <img src="{{ asset('assets/images/logo.png') }}"/>
        </div>
        <div class="col-print-4">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-print-7">
            <p>Name:</p>
            <p>Email:</p>
            <p>Experience:</p>
        </div>
        <div class="col-print-3" style="text-align: right; float: right;">
            <p style="margin-right: 98px;">Date:</p>
            <p>No. of Questions: {{ $questions->count() }}</p>
            <p>Duration: {{ $duration }} minutes</p>
        </div>
    </div>
    <hr/>
    @foreach($questions as $question)
        <div class="row mb-3 question-row">
            <p class="col-print-12">{{ $loop->iteration.'. '.$question->name }}</p>

            @if($question->type == 'O')
                <div class="col-print-12" style="margin-left: 20px;">
                    @foreach(json_decode($question->options) as $option)
                        <span class="checkbox"></span>&nbsp;&nbsp;{{ $option }}<br/>
                    @endforeach
                </div>
            @else
                <p class="col-print-12" style="margin-bottom: 20px;"></p>
            @endif
        </div>
    @endforeach
</div>
</body>
</html>