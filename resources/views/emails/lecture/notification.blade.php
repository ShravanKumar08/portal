@component('mail::message')
<h4> Hi!! </h4>
<h5 class="text-center">Lecture Details</h5>
<br>
<br>
<h5>Lecturer Name:<b>{{$lecture->employee->name}}</b></h5>
<h5>Title:<b>{{$lecture->title}}</b></h5>
<h5>Description:<b>{{$lecture->description}}</b></h5>
<h5>Date:<b>{{$lecture->date}}</b></h5>
<h5>Start Time:<b>{{$lecture->start}}</b></h5>
<h5>End Time:<b>{{$lecture->end}}</b></h5>
<a href={{url('employee/lectures?scope=Others')}}><button type="button" class="btn btn-success btn-lg">Portal Link</button></a>
@endcomponent