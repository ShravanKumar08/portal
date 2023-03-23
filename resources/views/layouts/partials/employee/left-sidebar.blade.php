<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <div class="user-profile">
            <!-- User profile image -->
            <div class="profile-img"> <img src="{{ @$auth_employee->avatar }}" alt="user" class="img-circle" />
                <!-- this is blinking heartbit-->
{{--                <div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>--}}
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

                {{--@if(@$auth_employee->timerStarted)--}}
                {{--<a href="{{ url('/employee/entry/timer/stop') }}" onclick="event.preventDefault();--}}
                        {{--document.getElementById('timer-stop-form').submit();"  class="" data-toggle="tooltip" title="Stop Timer"><i class="mdi mdi-alarm"></i></a>--}}
                {{--@else--}}
                {{--<a href="{{ url('/employee/report') }}" onclick="event.preventDefault();" title="Start Timer"><i class="mdi mdi-alarm"></i></a>--}}
                {{--<a href="{{ url('/employee/entry/timer/start') }}" onclick="event.preventDefault();--}}
                        {{--document.getElementById('timer-start-form').submit();" class="" data-toggle="tooltip" title="Start Timer"><i class="mdi mdi-alarm"></i></a>--}}
                {{--@endif--}}
                
                {{--<form id="timer-start-form" action="{{ route('employee.entry.start') }}" method="POST" style="display: none;">--}}
                    {{--@csrf--}}
                {{--</form>--}}
                
                <form id="timer-stop-form" action="{{ route('employee.entry.stop') }}" method="POST" style="display: none;">
                    @csrf
                </form>
               
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="mdi mdi-settings"></i></a>
                @impersonating
                    <a href="{{ route('impersonate.leave') }}" title="Leave impersonation"><i class="fa fa-power-off"></i></a>
                @else
                    <a href="#" data-toggle="tooltip" title="Logout" onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i></a>
                @endImpersonating
                <div class="dropdown-menu animated flipInY">
                    <!-- text-->
                    <a href="{{ route('employee.profile') }}" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                    <!-- text-->
                    <a href="{{ route('employee.profile') }}#changepassword" class="dropdown-item"><i class="ti-"></i> Update Password</a>
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
                <li class="nav-devider"></li>
                <li><a class="waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                <li class="active">
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-library-books"></i><span class="hide-menu">My Reports</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.report.index') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i>All</a></li>
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.report.index').'?scope=pendingApproved' }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i>Pending</a></li>
                        <li class="{{ date('Y-m-d') <= "2019-06-26" ? 'active' : '' }}">
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Monthly</a>
                            <ul aria-expanded="false" class="collapse">
                                <!-- <li><a class="waves-effect waves-dark" href="{{ route('employee.report.monthlyreports.dailyreport') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Pie Chart</a></li> -->
                                <li><a class="waves-effect waves-dark" href="{{ route('employee.report.monthlyreports.assessment') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Assessment</a></li>
                                <li><a class="waves-effect waves-dark" href="{{ route('employee.report.monthlyreports.breaktimings') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Break Timings</a></li>
                                <li>
                                    <a class="waves-effect waves-dark" href="{{ route('employee.report.monthlyreports.project') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Project
                                    @if(date('Y-m-d') <= "2019-06-26")
                                        <span class="label label-danger pull-right">New</span>
                                    @endif
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.report.create') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Today Report</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi  mdi-clipboard-check"></i><span class="hide-menu">Permission</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('employee.userpermission.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a></li>
                        <li><a href="{{ route('employee.userpermission.index').'?scope=pending' }}"><i class="mdi mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                        <li><a href="{{ route('employee.userpermission.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create New</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi  mdi-file-document"></i><span class="hide-menu">Leave</span></a>
                    <ul aria-expanded="false" class="collapse">
                     {{-- <li><a href="{{ route('employee.leave.index') }}"><i class="mdi mdi-chevron-double-right"></i>&nbsp;All</a></li> --}}
                     <li><a href="{{ route('employee.leave.index') }}"><i class="mdi mdi-chevron-double-right"></i>&nbsp;All</a></li>
                        <li><a href="{{ route('employee.leave.index').'?scope=pending' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                        <!-- <li><a href="{{ route('employee.report.monthlyreports.leavereport') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Monthly Report</a></li> -->
                        <!-- <li><a href="{{ route('employee.report.yearlyreports.leave') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Yearly Report</a></li> -->
                        <li><a href="{{ route('employee.leave.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create New</a></li>
                    </ul>
                </li>
                <li><a class="waves-effect waves-dark" href="{{ route('employee.compensation.index') }}" aria-expanded="false"><i class="mdi  mdi-bookmark-plus"></i><span class="hide-menu">Compensations</span></a></li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-alarm-check"></i><span class="hide-menu">Entries</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.entry.index') }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> All</a></li>
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.late_entries.index') }}" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Late Entries</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-card-details"></i><span class="hide-menu">Lectures</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.lectures.index').'?scope=Self' }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Self</a></li>
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.lectures.index').'?scope=Others' }}" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Others</a></li>
                    </ul>
                </li>
                <li><a class="waves-effect waves-dark" href="{{ route('employee.holiday.index') }}" aria-expanded="false"><i class="mdi mdi-star"></i><span class="hide-menu">Holidays</span></a></li>
                <!-- <li><a class="waves-effect waves-dark" href="{{ route('employee.polls') }}" aria-expanded="false"><i class="mdi mdi-poll"></i><span class="hide-menu">Polls</span></a></li> -->
                <li><a class="waves-effect waves-dark" href="{{ route('employee.composemail') }}" aria-expanded="false"><i class="mdi mdi-message-text-outline"></i><span class="hide-menu">Contact</span></a></li>
                <li><a class="waves-effect waves-dark" href="{{ route('employee.usersettings.index') }}" aria-expanded="false"><i class="mdi mdi-settings-box"></i><span class="hide-menu">Settings</span></a></li>
                <li><a class="waves-effect waves-dark" href="{{ route('employee.skills.index') }}" aria-expanded="false"><i class="mdi mdi-settings-box"></i><span class="hide-menu">Skills</span></a></li>
                <li><a class="waves-effect waves-dark" href="{{ route('employee.idps.index') }}" aria-expanded="false"><i class="mdi mdi-settings-box"></i><span class="hide-menu">IDP</span></a></li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-card-details"></i><span class="hide-menu">Assesment</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.evaluation.index').'?scope=self'  }}" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i> Self</a></li>
                        <li><a class="waves-effect waves-dark" href="{{ route('employee.evaluation.index').'?scope=others' }}" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Others</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
