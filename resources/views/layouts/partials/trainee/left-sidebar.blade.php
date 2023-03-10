<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <div class="user-profile">
            <!-- User profile image -->
            <div class="profile-img"> <img src="{{ @$auth_employee->avatar }}" alt="user" class="img-circle" />
                <!-- this is blinking heartbit-->
                @if(@$auth_employee->timerStarted)
                    <span class="profile-status online pull-right"></span>
                @else
                    <span class="profile-status busy pull-right"></span>
                @endif
            </div>
            <!-- User profile text-->
            <div class="profile-text">
                <h5>{{ @$auth_employee->name }}</h5>
                <h5 id="countdown-timer"></h5>

                @if(@$auth_employee->timerStarted)
                    <a href=""  onclick="event.preventDefault();"  data-toggle="tooltip" id="stoptimer" title="Stop Timer"><i class="mdi mdi-alarm"></i></a>
                @endif
            
                {{--<form id="timer-start-form" action="{{ route('employee.entry.start') }}" method="POST" style="display: none;">--}}
                    {{--@csrf--}}
                {{--</form>--}}
                
                <form id="timer-stop-form" action="{{ route('trainee.entry.stop') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-settings"></i></a>
                @impersonating
                    <a href="{{ route('impersonate.leave') }}" title="Leave impersonation" id="sa-success"><i class="fa fa-power-off"></i></a>
                @else
                    <a href="#" data-toggle="tooltip" title="Logout" onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i></a>
                @endImpersonating
               
                <div class="dropdown-menu animated flipInY">
                    <!-- text-->
                    <a href="{{ route('trainee.profile') }}" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                    <!-- text-->
                    <a href="{{ route('trainee.profile') }}#changepassword" class="dropdown-item"><i class="ti-"></i> Update Password</a>
                    <!-- text-->
                    <div class="dropdown-divider"></div>
                    <!-- text-->
                    <a href="#" class="dropdown-item" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a>
                    <!-- text-->
                </div>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li><a class="waves-effect waves-dark" href="{{ route('trainee.dashboard') }}" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                
            <li>
                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-alarm-check"></i><span class="hide-menu">Entries</span></a>
                <ul aria-expanded="false" class="collapse">
                    <li><a href="{{ route('trainee.entry.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a></li>
                    <li><a href="{{ route('trainee.late_entries.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Late Entries</a></li>
                </ul>
            </li>

            <li class="active">
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-library-books"></i><span class="hide-menu">My Reports</span></a>
                    <ul aria-expanded="false" class="collapse">
                            <li><a class="waves-effect waves-dark" href="{{ route('trainee.report.index') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i>All</a></li>
                            <li><a class="waves-effect waves-dark" href="{{ route('trainee.report.create') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Today Report</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-card-details"></i><span class="hide-menu">Lectures</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a class="waves-effect waves-dark" href="{{ route('trainee.lectures.index').'?scope=Self' }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Self</a></li>
                        <li><a class="waves-effect waves-dark" href="{{ route('trainee.lectures.index').'?scope=Others' }}" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Others</a></li>
                    </ul>
                </li>

                @if(!@$auth_employee->timerStarted && !@$auth_employee->traineeCanRequestTimer)
                    <li><a class="waves-effect waves-dark" href="{{ route('trainee.entry.timeronrequest') }}" aria-expanded="false"><i class="mdi mdi-alarm"></i><span class="hide-menu">Timer On Request</span></a></li>
                @endif
                <li><a class="waves-effect waves-dark" href="{{ route('trainee.polls') }}" aria-expanded="false"><i class="mdi mdi-poll"></i><span class="hide-menu">Polls</span></a></li>
            
            <li>
                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi  mdi-clipboard-check"></i><span class="hide-menu">Permission</span></a>
                <ul aria-expanded="false" class="collapse">
                    <li><a href="{{ route('trainee.userpermission.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a></li>
                    <li><a href="{{ route('trainee.userpermission.index').'?scope=pending' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                    <li><a href="{{ route('trainee.userpermission.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create New</a></li>
                </ul>
            </li>
            <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi  mdi-file-document"></i><span class="hide-menu">Leave</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('trainee.leave.index') }}"><i class="mdi mdi-chevron-double-right"></i>&nbsp;All</a></li>
                        <li><a href="{{ route('trainee.leave.index').'?scope=pending' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                        {{-- <li><a href="{{ route('trainee.report.monthlyreports.leavereport') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Monthly Report</a></li> --}}
                        {{-- <li><a href="{{ route('trainee.report.yearlyreports.leave') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Yearly Report</a></li> --}}
                        <li><a href="{{ route('trainee.leave.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create New</a></li>
                    </ul>
        </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
@push('scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#stoptimer').click(function(a){
                swal({
                        title:"Warning !",
                        text: 'You are not allowed to SWTICH OFF timer before completing Total Hours',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Do you still want to Switch off",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    }, function () {
                        document.getElementById('timer-stop-form').submit();
                    });
            });
        });     
    </script>
@endpush
