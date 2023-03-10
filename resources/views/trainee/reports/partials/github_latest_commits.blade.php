<?php
    use Illuminate\Support\Str;
?>
@if($values['username'] == '')
 
 <div class="form-group">
      Please click below button to submit your github credentials to proceed.
  </div>
<div class="modal-footer center">
    <input class="btn btn-success" type="button" value="Github Credentials" onclick="window.location.href='{{ url('employee/usersettings/'.$userSettings->id.'/edit')}}'" />
</div>

 @else

<table class="table color-table success-table color-bordered-table success-bordered-table" id="github-table" border='0'>
    <thead>
    <tr>
        <th>Projects</th>
        <th>Commits</th>
        <th>Time</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        @forelse (@$githubItems as $githubItem => $githubs)
        <tr>
           <td>{{  Str::title($githubs['repository']) }}</td>
           <td class="apply-message">{{ $githubs['message'] }}</td>
           <td>{{ \Carbon\Carbon::parse($githubs['date'])->format('d-m-Y h:i A') }}</td>
           <td><button type="button" class="btn btn-xs waves-effect waves-light btn-primary apply-commit">Apply</button></td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">No records..</td>
        </tr>
        
         @endforelse
    </tbody>
</table>
@endif
