<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <div class="user-profile">
            <!-- User profile image -->
            <div class="profile-img">
                <img class="img-circle" src="{{ $logo_light_icon }}" alt="user"/>
                <!-- this is blinking heartbit-->
                {{--<div class="notify setpos"> <span class="heartbit"></span> <span class="point"></span> </div>--}}
            </div>
            <!-- User profile text-->
            <div class="profile-text">
                <h5>{{ \Auth::user()->name }}</h5>
                <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="true"><i class="mdi mdi-settings"></i></a>
                {{--<a href="#" class="" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>--}}
                <a href="#" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" data-toggle="tooltip" title="Logout"><i
                            class="fa fa-power-off"></i></a>
                <div class="dropdown-menu animated flipInY">
                    <!-- text-->
                    @hasAccess('myprofile')
                    <a href="{{ route('myprofile') }}" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                    @endhasAccess
                    <div class="dropdown-divider"></div>
                    <!-- text-->
                    @hasAccess('setting.index')
                    <a href="{{ route('setting.index') }}" class="dropdown-item"><i class="ti-settings"></i> Account
                        Setting</a>
                    @endhasAccess
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
                <li>
                    <a class="waves-effect waves-dark" href="{{ route('home') }}" aria-expanded="false">
                        <i class="mdi mdi-gauge"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-box"></i><span class="hide-menu">Employees</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('employee.index')
                        <li><a href="{{ route('employee.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                        </li>
                        @endhasAccess

                        @hasAccess('employee.index')
                        <li><a href="{{ route('employee.index').'?status=inactive&employeetype=P' }}"><i
                                        class="mdi  mdi-chevron-double-right"></i>&nbsp;Inactive</a></li>
                        @endhasAccess

                        @hasAccess('employee.create')
                        <li><a href="{{ route('employee.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                New</a></li>
                        @endhasAccess
                        
                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                        class="mdi  mdi-chevron-double-right"></i><span>Permission</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('userpermission.index')
                                <li><a href="{{ route('userpermission.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('userpermission.index')
                                <li><a href="{{ route('userpermission.index').'?scope=pending&employeetype=P' }}"><i
                                                class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                                @endhasAccess
        
                                @hasAccess('userpermission.create')
                                <li><a href="{{ route('userpermission.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
                            </ul>
                        </li>

                         <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                            class="mdi  mdi-chevron-double-right"></i><span>Temporary Cards</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('tempcard.index')
                                <li><a href="{{ route('tempcard.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess

                                @hasAccess('tempcard.create')
                                <li><a href="{{ route('tempcard.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                        class="mdi mdi-chevron-double-right"></i><span>Leave</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('leave.index')
                                <li><a href="{{ route('leave.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('leave.index')
                                <li><a href="{{ route('leave.index').'?scope=pending&employeetype=P' }}"><i
                                                class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                                @endhasAccess
        
                                @hasAccess('report.monthlyreports.leavereport')
                                <li><a href="{{ route('report.monthlyreports.leavereport') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Monthly Report</a></li>
                                @endhasAccess
        
                                @hasAccess('report.yearlyreports.leave')
                                <li><a href="{{ route('report.yearlyreports.leave') }}"
                                       aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i><span class="hide-menu">Yearly Report</span></a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('leave.create')
                                <li><a href="{{ route('leave.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
                            </ul>
                        </li>
                        @hasAccess('entry.index')
                         <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                        class="mdi mdi-chevron-double-right"></i><span>Entries</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('entry.index')
                                <li><a href="{{ route('entry.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('entry.create')
                                <li><a href="{{ route('entry.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
        
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Late Entries</a>
                                    <ul aria-expanded="false" class="collapse">
                                        @hasAccess('late_entries.index')
                                        <li><a href="{{ route('late_entries.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                        </li>
                                        @endhasAccess
        
                                        @hasAccess('late_entries.create')
                                        <li><a href="{{ route('late_entries.create').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                                New</a></li>
                                        @endhasAccess
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endhasAccess
                        @hasAccess('employee.upcoming_birthday')
                        <li><a href="{{ route('employee.upcoming_birthday').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Upcoming Birthday</a></li>
                        @endhasAccess

                        @hasAccess('employee.break_timings')
                        <li><a href="{{ route('employee.break_timings').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Break Timings</a></li>
                        @endhasAccess

                        @hasAccess('teams.index')
                        <li><a href="{{ route('teams.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Teams</a></li>
                        @endhasAccess
                    </ul>
                </li>

                {{-- <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-box-outline"></i><span class="hide-menu">Trainees</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('employee.index')
                        <li><a href="{{ route('employee.index').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                        </li>
                        @endhasAccess

                        @hasAccess('employee.index')
                                <li>
                                    <a href="{{ route('employee.index').'?status=inactive&employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Inactive
                                    </a>
                                </li>
                        @endhasAccess

                        @hasAccess('employee.create')
                        <li><a href="{{ route('employee.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                New</a></li>
                        @endhasAccess
                        
                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                        class="mdi  mdi-chevron-double-right"></i><span>Permission</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('userpermission.index')
                                <li><a href="{{ route('userpermission.index').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('userpermission.index')
                                <li><a href="{{ route('userpermission.index').'?scope=pending&employeetype=T' }}"><i
                                                class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                                @endhasAccess
        
                                @hasAccess('userpermission.create')
                                <li><a href="{{ route('userpermission.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                        class="mdi mdi-chevron-double-right"></i><span>Leave</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('leave.index')
                                <li><a href="{{ route('leave.index').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('leave.index')
                                <li><a href="{{ route('leave.index').'?scope=pending&employeetype=T' }}"><i
                                                class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending</a></li>
                                @endhasAccess

                                @hasAccess('leave.create')
                                <li><a href="{{ route('leave.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
                            </ul>
                        </li>

                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                        class="mdi mdi-chevron-double-right"></i><span>Entries</span></a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('entry.index')
                                <li><a href="{{ route('entry.index').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                </li>
                                @endhasAccess
        
                                @hasAccess('entry.create')
                                <li><a href="{{ route('entry.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                        New</a></li>
                                @endhasAccess
                                <li>
                                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Late Entries</a>
                                    <ul aria-expanded="false" class="collapse">
                                        @hasAccess('late_entries.index')
                                        <li><a href="{{ route('late_entries.index').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                                        </li>
                                        @endhasAccess
        
                                        @hasAccess('late_entries.create')
                                        <li><a href="{{ route('late_entries.create').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                                New</a></li>
                                        @endhasAccess
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @hasAccess('employee.upcoming_birthday')
                        <li><a href="{{ route('employee.upcoming_birthday').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Upcoming Birthday</a></li>
                        @endhasAccess

                        @hasAccess('employee.trainee_breaktimings')
                        <li><a href="{{ route('employee.trainee_breaktimings').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Break Timings</a></li>
                        @endhasAccess

                    </ul>
                </li> --}}
                @hasAccess('report.index')
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-library-books"></i><span class="hide-menu">Reports</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('report.index')
                        <li><a href="{{ route('report.index').'?employeetype=P' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Employees</a>
                        </li>
                        @endhasAccess
                        {{-- @hasAccess('report.index')
                        <li><a href="{{ route('report.index').'?employeetype=T' }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Trainees</a>
                        </li>
                        @endhasAccess --}}

                        @hasAccess('report.index')
                        <li><a href="{{ route('report.index').'?scope=releaselock' }}"><i
                                        class="mdi  mdi-chevron-double-right"></i>&nbsp;Break Lock/Release</a></li>
                        <li><a href="{{ route('report.index').'?scope=pendingnottoday' }}"><i
                                        class="mdi  mdi-chevron-double-right"></i>&nbsp;Pending Reports</a></li>
                        <li><a href="{{ route('report.index').'?scope=noreport' }}"><i
                                        class="mdi  mdi-chevron-double-right"></i>&nbsp;No Reports</a></li>
                        @endhasAccess

                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi  mdi-chevron-double-right"></i> Monthly</a>
                            <ul aria-expanded="false" class="collapse">
                                @hasAccess('report.monthlyreports.dailyreport')
                                <li><a href="{{ route('report.monthlyreports.dailyreport') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Pie Chart</a></li>
                                @endhasAccess

                                @hasAccess('report.monthlyreports.assessment')
                                <li><a href="{{ route('report.monthlyreports.assessment') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Assessment</a></li>
                                @endhasAccess
                               
                                @hasAccess('report.monthlyreports.project')
                                <li><a href="{{ route('report.monthlyreports.project') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Project</a></li>
                                @endhasAccess
                            </ul>
                        </li>

                        @hasAccess('report.create')
                        <li><a href="{{ route('report.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                New</a></li>
                        @endhasAccess

                    </ul>
                </li>
                @endhasAccess
                @hasAccess('compensation.index')
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi  mdi-bookmark-plus"></i><span class="hide-menu">Compensations</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('compensation.index')
                        <li><a href="{{ route('compensation.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                        </li>
                        @endhasAccess

                        @hasAccess('compensation.create')
                        <li><a href="{{ route('compensation.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                New</a></li>
                        @endhasAccess
                    </ul>
                </li>
                @endhasAccess
                @hasAccess('compensation.index')
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-account-card-details"></i><span class="hide-menu">Lectures</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('lectures.index')
                        <li><a href="{{ route('lectures.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;All</a>
                        </li>
                        @endhasAccess

                        @hasAccess('lectures.create')
                        <li><a href="{{ route('lectures.create') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Create
                                New</a></li>
                        @endhasAccess
                    </ul>
                </li>
                @endhasAccess
                @hasAccess('officetimingslot.index')
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-timer-sand"></i><span class="hide-menu">Office Timings</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('officetimingslot.index')
                        <li><a href="{{ route('officetimingslot.index') }}"><i class="mdi mdi-chevron-double-right"></i>
                                Slots</a></li>
                        @endhasAccess
                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chevron-double-right"></i><span class="hide-menu">Timings</span></a>
                        @hasAccess('officetiming.index')
                        <ul>
                        <li><a href="{{ route('officetiming.index').'?scope=P' }}"><i class="mdi mdi-chevron-double-right"></i>&nbsp;Employees</a>
                        </li>
                        {{-- <li><a href="{{ route('officetiming.index').'?scope=T' }}"><i class="mdi mdi-chevron-double-right"></i>&nbsp;Trainees</a> --}}
                        </li>
                    </ul>
                        @endhasAccess
                    </ul>
                </li>
                @endhasAccess
                <li>
                     @hasAccess('setting.index')
                        <a class="waves-effect waves-dark" href="{{ route('setting.generate_payslip') }}" aria-expanded="false">
                            <i class="mdi mdi-cash-100"></i>
                            <span class="hide-menu">Payslips</span>
                        </a>
                    @endhasAccess
                </li>

                @hasAccess('schedule.index')
                        <li>
                            <a href="{{ route('schedule.index') }}"><i class="fa fa-calendar"></i>&nbsp;Schedule</a>
                        </li>
                @endhasAccess

                <li>
                    @hasAccess('setting.index')
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i
                                class="mdi mdi-settings-box"></i><span class="hide-menu">Settings</span></a>
                    @endhasAccess
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('setting.index')
                        <li><a href="{{ route('setting.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;App
                                Settings</a></li>
                        @endhasAccess

                        @hasAccess('setting.index')
                        <li><a href="{{ route('setting.index').'?scope=emailtemplate' }}"><i
                                        class="mdi  mdi-chevron-double-right"></i>&nbsp;Email Template</a></li>
                        @endhasAccess

                        @hasAccess('designation.index')
                        <li><a href="{{ route('designation.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Designation</a>
                        </li>
                        @endhasAccess

                        @hasAccess('project.index')
                        <li><a href="{{ route('project.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Projects</a>
                        </li>
                        @endhasAccess

                        @hasAccess('technology.index')
                        <li><a href="{{ route('technology.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Task
                                Criteria</a></li>
                        @endhasAccess

                        @hasAccess('holiday.index')
                        <li><a href="{{ route('holiday.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Holidays</a>
                        </li>
                        @endhasAccess

                        @hasAccess('customfield.index')
                        <li><a href="{{ route('customfield.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Custom
                            Fields</a></li>
                        @endhasAccess

                        @hasAccess('setting.index')
                        <li><a href="{{ route('project.mergeproject') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;
                            MergeProject</a></li>
                        @endhasAccess
                    </ul>
                </li>
                <!-- @hasAccess('poll.index')
                <li><a class="waves-effect waves-dark" href="{{ route('poll.index') }}" aria-expanded="false"><i
                                class="mdi mdi-poll"></i><span class="hide-menu">Polls</span></a></li>
                @endhasAccess -->
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Question Bank</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('platform.index')
                            <li><a href="{{ route('platform.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Platforms</a></li>
                        @endhasAccess

                        @hasAccess('grade.index')
                            <li><a href="{{ route('grade.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Grades</a></li>
                        @endhasAccess

                        @hasAccess('question.index')
                            <li><a href="{{ route('question.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Questions</a></li>
                        @endhasAccess

                        @hasAccess('question.generate')
                            <li><a href="{{ route('question.generate') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp;Generate Q/A</a></li>
                        @endhasAccess
                </ul>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Interview</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @hasAccess('interviewcall.index')
                            <li><a href="{{ route('interviewcall.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp; Profile & Schedule</a></li>
                        @endhasAccess

                        {{-- @hasAccess('interviewstatus.index')
                            <li><a href="{{ route('interviewstatus.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp; Status</a></li>
                        @endhasAccess --}}
                        @hasAccess('interviewpre-screening.index')
                        <li><a href="{{ route('interviewprescreening.index') }}"><i class="mdi  mdi-chevron-double-right"></i>&nbsp; Pre-Screening</a></li>
                        @endhasAccess
                    </ul>
                </li>
                @hasAccess('assesment.index')
                <li><a class="waves-effect waves-dark" href="{{ route('assesment.index') }}" aria-expanded="false"><i
                                class="mdi mdi-account-card-details"></i><span class="hide-menu">Assesment</span></a></li>
                @endhasAccess
                @hasAccess('skills.index')
                <li><a class="waves-effect waves-dark" href="{{ route('skills.index') }}" aria-expanded="false"><i
                                class="mdi mdi-account-card-details"></i><span class="hide-menu">Skills</span></a></li>
                @endhasAccess
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
