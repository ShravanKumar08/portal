<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::get('/test', function () {
    return 'test';
})->name('test');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Auth::routes();

Route::any('camsunit/attendance', 'CamsunitController@attendance')->middleware(\App\Http\Middleware\CheckCamsunit::class);

Route::get('/image/resize/{size}', 'ImageController@getImage');

Route::get('/backup_download', 'HomeController@backup_download');

/*for page idle checking*/
//Route::get('/keep-alive', function () {
//    return 1;
//})->name('keep-alive');
//
// Impersonate
Route::impersonate();
Route::get('/impersonate/take_redirect', 'HomeController@impersonateTakeRedirect');

Route::get('force_download/{export}', 'HomeController@forceDownload');

Route::get('backup_download', 'HomeController@backup_download');

Route::prefix('admin')->namespace('Admin')->middleware(['role:admin|super-user', 'admin', 'web', 'sentry'])->group(function () {
    //User Routes
    Route::get('dashboard', 'UserController@dashboard')->name('dashboard');
    Route::post('calendar/events', 'UserController@calendar_events')->name('calendar_events');
    Route::match(['get', 'post'], 'profile', 'UserController@myprofile')->name('myprofile');
    Route::post('user/{id}','UserController@active')->name('user.active');

    //Entry Routes
    Route::get('entry/entryitems/{id}', 'EntryController@getentryitems')->name('entry.entryitems');
    Route::post('entry/addremarks', 'EntryController@addremarks')->name('entry.addremarks');

    //Setting Routes
    Route::get('setting/payslip', 'SettingController@payslip')->name('payslip');

    //Employee Routes
    Route::get('employee/searchdesignation', 'EmployeeController@searchDesignation')->name('employee.searchdesignation');
    Route::get('employee/access/{id}', 'EmployeeController@access')->name('employee.access');
    Route::post('employee/access_store/{id}', 'EmployeeController@access_store')->name('employee.access_store');
    Route::get('employee/login/{id}', 'EmployeeController@login')->name('employee.login');
    Route::get('employee/upcoming_birthday', 'EmployeeController@upcoming_birthday')->name('employee.upcoming_birthday');
    Route::match(['get', 'post'],'employee/mailoverride/{id}', 'EmployeeController@overridreportmail')->name('employee.mailoverride');
    Route::get('employee/breaktimings', 'EmployeeController@getbreakTimingReports')->name('employee.break_timings');
    Route::get('employee/monthlybreaks', 'EmployeeController@employeeMonthlyBreaks')->name('employee.monthlybreaks');
    Route::get('employee/traineebreaktimings', 'EmployeeController@getTraineebreakTimingReports')->name('employee.trainee_breaktimings');
    Route::get('employee/traineemonthlybreaks', 'EmployeeController@TraineeMonthlyBreaks')->name('employee.trainee_monthlybreaks');

    //User permission Routes
    Route::post('userpermission/addremarks', 'UserpermissionController@addremarks')->name('userpermission.addremarks');
    Route::post('userpermission/bulkchangestatus', 'UserpermissionController@bulkchangestatus')->name('userpermission.bulkchangestatus');
    Route::get('userpermission/getpermissionform', 'UserpermissionController@getPermissionform')->name('userpermission.getpermissionform');
    Route::get('userpermission/showaudits', 'UserpermissionController@showAudits')->name('userpermission.audits');

    //leave routes
    Route::post('leave/addremarks', 'LeaveController@addremarks')->name('leave.addremarks');
    Route::post('leave/bulkchangestatus', 'LeaveController@bulkchangestatus')->name('leave.bulkchangestatus');
    Route::get('leave/getleaveform', 'LeaveController@getLeaveform')->name('leave.getleaveform');
    Route::match(['get', 'post'],'leave/toggleleave', 'LeaveController@toggleLeave')->name('leave.toggleLeave');
    Route::match(['get', 'post'],'leave/convertleave', 'LeaveController@convertLeave')->name('leave.convertLeave');
    Route::get('leave/showaudits', 'LeaveController@showAudits')->name('leave.audits');

     //late_entries routes
    Route::post('late_entries/addremarks', 'LateEntryController@addremarks')->name('late_entries.addremarks');

     //report routes
    Route::post('report/addremarks', 'ReportController@addremarks')->name('report.addremarks');
    Route::post('report/bulkchangestatus', 'ReportController@bulkchangestatus')->name('report.bulkchangestatus');
    Route::get('report/monthlyreports/assessment', 'ReportController@getMonthlyAssessmentReport')->name('report.monthlyreports.assessment');
    Route::get('report/monthlyreports/project', 'ReportController@getMonthlyWorkingHoursReport')->name('report.monthlyreports.project');
    Route::get('report/monthlyreports/showproject', 'ReportController@showMonthlyWorkingHoursReport')->name('report.monthlyreports.showproject');

    Route::get('report/monthlyreports/dailyreport', 'ReportController@getDailyMonthlyReport')->name('report.monthlyreports.dailyreport');
    Route::get('report/monthlyreports/leavereport', 'ReportController@getLeaveMonthlyReport')->name('report.monthlyreports.leavereport');
    Route::post('report/monthlyreports/getleaveitems', 'ReportController@getLeaveItems')->name('report.monthlyreports.getleaveitems');
    Route::post('report/monthlyreports/getmonthlyreportitems', 'ReportController@getMonthlyReportItems')->name('report.monthlyreports.getmonthlyreportitems');
    Route::match(['get','post'],'report/releaserequest', 'ReportController@releaseRequest')->name('report.releaserequest');
    Route::get('report/yearlyreports/leave', 'ReportController@getLeaveYearlyReport')->name('report.yearlyreports.leave');
    Route::post('report/yearlyreports/getleaveitems', 'ReportController@getyearlyleaveitems')->name('report.yearlyreports.getleaveitems');
    Route::match(['get','post'],'report/getreportitemsedit', 'ReportController@getReportitemsedit')->name('report.getreportitemsedit');
    Route::post('report/reportitem/store', 'ReportController@storeReportitem')->name('report.storeReportitem');
    Route::put('report/reportitem/update/{id}', 'ReportController@updateReportitem')->name('report.updateReportitem');
    Route::post('report/getendtime', 'ReportController@getEndTime')->name('report.getendtime');

    Route::get('report/searchproject', 'ReportController@searchProject')->name('report.searchproject');
    Route::post('report/searchtechnolgy', 'ReportController@searchTechnolgy')->name('report.searchtechnolgy');
    Route::post('report/setpermissiontime', 'ReportController@setPermissionTime')->name('report.setPermissionTime');
    Route::delete('report/deletereportitems','ReportController@deleteReportitems')->name('report.deleteReportitems');
    Route::get('report/getreportitems', 'ReportController@getReportitems')->name('report.getReportitems');

    //Setting Routes
    Route::get('setting/searchuseremail', 'SettingController@searchUserEmail')->name('setting.searchUserEmail');
    Route::post('setting/emailpreview', 'SettingController@emailpreview')->name('setting.emailpreview');
   
    //Officetiming Routes
    Route::post('officetiming/slotsave', 'OfficetimingController@slotSave')->name('officetiming.slotSave');
    Route::post('officetiming/slot_events', 'OfficetimingController@slot_events')->name('officetiming.slot_events');
    Route::post('schedule/slotsave', 'ScheduleController@slotSave')->name('schedule.slotSave');
    Route::post('schedule/slot_events', 'ScheduleController@slot_events')->name('schedule.slot_events');
    
    //Payslip
    Route::match(['get', 'post'], 'generatepayslip', 'SettingController@generatePayslip')->name('setting.generate_payslip');
    Route::post('getEmployeePayslipForm', 'SettingController@getEmployeePayslipForm')->name('setting.getEmployeePayslipForm');
    Route::match(['get', 'post'], 'calculatepayslip', 'SettingController@calculatepayslip')->name('setting.calculatepayslip');

    //polls
    Route::get('viewvotes/{id}', ['uses' => 'UserController@viewVotes'])->name('viewVotes');

    //Designation routes
    Route::post('designation/{id}','DesignationController@active')->name('designation.active');

    //Designation routes
    Route::post('tempcard/{id}','TempcardController@active')->name('tempcard.active');
    
    //Project routes
    Route::match(['get', 'post'], 'project/mergeproject', 'ProjectController@mergeProject')->name('project.mergeproject');
    Route::post('project/{id}','ProjectController@active')->name('project.active');
    Route::get('project/showaudits', 'ProjectController@showAudits')->name('project.audits');
    
    //Technology routes
    Route::post('technology/{id}','TechnologyController@active')->name('technology.active');
    
    //Compensation
    Route::get('compensation/getcompensationform', 'CompensationController@getCompensationform')->name('compensation.getcompensationform');
    Route::post('compensation/addremarks', 'CompensationController@addremarks')->name('compensation.addremarks');
   
    //Questions
    Route::match(['get', 'post'], 'question/generate', 'QuestionController@generate')->name('question.generate');
    Route::post('question/download', 'QuestionController@download')->name('question.download');
    Route::get('getEntryDetail', 'LateEntryController@getEntrydetail');

    //Interview
    Route::post('interviewstatus/{id}','InterviewStatusController@active')->name('active');
    Route::post('callstatus/{id}','InterviewCallController@status')->name('status');
    Route::post('getcandidates','InterviewCallController@getcandidates')->name('interviewcall.getcandidates');
    Route::get('getinterviewcalls/{id}','InterviewCallController@showInterviewCall')->name('getinterviewcalls');

    //Lectures
    Route::get('lectures/form','LectureController@form')->name('lectures.form');
    Route::get('lectures/view/{id}','LectureController@list')->name('lectures.list');
    Route::post('lectures/delete','LectureController@deleteLecture')->name('lectures.deleteLecture');
    Route::get('lectures/getemployees','LectureController@getEmployees')->name('lectures.getemployees');
    Route::post('lectures/markattendance','LectureController@markAttendance')->name('lectures.markattendance');

    //Resources
    Route::resource('employee', 'EmployeeController');
    Route::resource('setting', 'SettingController');
    Route::resource('schedule', 'ScheduleController');
    Route::resource('project', 'ProjectController');
    Route::resource('technology', 'TechnologyController');
    Route::resource('holiday', 'HolidayController');
    Route::resource('designation', 'DesignationController');
    Route::resource('userpermission', 'UserpermissionController');
    Route::resource('leave', 'LeaveController');
    Route::resource('lectures','LectureController');
    Route::resource('customfield', 'CustomfieldController');
    Route::resource('entry', 'EntryController');
    Route::resource('officetiming', 'OfficetimingController');
    Route::resource('officetimingslot', 'OfficetimingslotController');
    Route::resource('late_entries', 'LateEntryController');
    Route::resource('compensation', 'CompensationController');
    Route::resource('platform', 'PlatformController');
    Route::resource('grade', 'GradeController');
    Route::resource('question', 'QuestionController');
    Route::resource('report', 'ReportController');
    Route::resource('tempcard', 'TempcardController');
    Route::resource('interviewcall', 'InterviewCallController');
    Route::resource('interviewstatus', 'InterviewStatusController');
    Route::resource('interviewprescreening', 'InterviewPrescreeningController');
    Route::resource('assesment', 'AssesmentController');
    Route::resource('teams', 'TeamController');
    Route::resource('skills', 'SkillController');
    Route::post('assesment/reupdate', 'AssesmentController@reupdate')->name('assesment.reupdate');
    Route::get('/evaluation/{id}/pdf', 'AssesmentController@evaluation_download')->name('assesment.evaluation_download');
});

Route::prefix('employee')->namespace('Employee')->as('employee.')->middleware(['role:employee', 'web'])->group(function () {
    Route::get('dashboard', 'UserController@dashboard')->name('dashboard');
    Route::match(['get', 'post'], 'profile', 'UserController@myprofile')->name('profile');
    Route::match(['get', 'post'], 'changepassword', 'UserController@changepassword')->name('changepassword');
    Route::post('calendar/events', 'UserController@calendar_events')->name('calendar_events');

    //Polls
    Route::get('polls', ['uses' => 'UserController@polls'])->name('polls');
    Route::get('vote/{id}', ['uses' => 'UserController@vote'])->name('vote');
    
    Route::match(['get', 'post'], 'contact', 'UserController@composemail')->name('composemail');
    Route::post('contact/composefileupload', 'UserController@composemail')->name('composefileupload');
    Route::post('contact/composefiledelete', 'UserController@composeFileDelete')->name('composeFileDelete');
    Route::get('searchuseremail', 'UserController@searchuseremail')->name('searchUserEmail');

    //Entry routes
    Route::get('entry', 'EntryController@index')->name('entry.index');
//    Route::post('entry/timer/start', 'EntryController@start')->name('entry.start');
    Route::post('entry/timer/stop', 'EntryController@stop')->name('entry.stop');
    //Route::get('entry/view/{id}', 'EntryController@show')->name('entry.show');
    Route::get('entry/entryitems/{id}', 'EntryController@getentryitems')->name('entry.entryitems');
    
    //Late Entry routes
     Route::get('late_entries', 'LateEntryController@index')->name('late_entries.index');
     
    //Report routes
    Route::post('report/reportitem/store', 'ReportController@storeReportitem')->name('report.storeReportitem');
    Route::put('report/reportitem/update/{id}', 'ReportController@updateReportitem')->name('report.updateReportitem');
    Route::get('report/searchproject', 'ReportController@searchProject')->name('report.searchproject');
    Route::post('report/searchtechnolgy', 'ReportController@searchTechnolgy')->name('report.searchtechnolgy');
    Route::get('report/getreportitems', 'ReportController@getReportitems')->name('report.getReportitems');
    Route::post('report/getreportitemform', 'ReportController@getReportitemForm')->name('report.getReportitemForm');
    Route::post('report/updatereportitems', 'ReportController@updateReportitems')->name('report.updateReportitems');
    Route::delete('report/deletereportitems','ReportController@deleteReportitems')->name('report.deleteReportitems');
    Route::post('report/timeronrequest', 'ReportController@timeronrequest')->name('report.timeronrequest');
    Route::match(['get','post'], 'report/releaselockbreak', 'ReportController@releaselockbreak')->name('report.releaselockbreak');
    Route::match(['get','post'], 'report/extendhours', 'ReportController@extendhours')->name('report.extendhours');
    Route::get('report/monthlyreports/assessment', 'ReportController@getMonthlyAssessmentReport')->name('report.monthlyreports.assessment');
    Route::get('report/monthlyreports/breaktimings', 'ReportController@getMonthlyBreaktimingsReport')->name('report.monthlyreports.breaktimings');
    Route::get('report/monthlybreaks', 'ReportController@employeeMonthlyBreaks')->name('report.monthlybreaks');
  
    Route::get('report/monthlyreports/project', 'ReportController@getMonthlyWorkingHoursReport')->name('report.monthlyreports.project');
    Route::get('report/monthlyreports/showproject', 'ReportController@showMonthlyWorkingHoursReport')->name('report.monthlyreports.showproject');

    Route::get('report/monthlyreports/dailyreport', 'ReportController@getDailyMonthlyReport')->name('report.monthlyreports.dailyreport');
    Route::post('report/monthlyreports/getmonthlyreportitems', 'ReportController@getMonthlyReportItems')->name('report.monthlyreports.getmonthlyreportitems');
    Route::get('report/monthlyreports/leavereport', 'ReportController@getLeaveMonthlyReport')->name('report.monthlyreports.leavereport');
    Route::post('report/monthlyreports/getleaveitems', 'ReportController@getLeaveItems')->name('report.monthlyreports.getleaveitems');
    Route::get('report/yearlyreports/leave', 'ReportController@getLeaveYearlyReport')->name('report.yearlyreports.leave');
    Route::post('report/yearlyreports/getleaveitems', 'ReportController@getyearlyleaveitems')->name('report.yearlyreports.getleaveitems');
    Route::post('report/setpermissiontime', 'ReportController@setPermissionTime')->name('report.setPermissionTime');
    Route::match(['get','post'], 'report/getgithubCommits', 'ReportController@getgithubCommits')->name('report.getgithubCommits');
    Route::match(['get','post'], 'report/createprojectname', 'ReportController@createprojectname')->name('report.createprojectname');
    Route::post('report/order/update','ReportController@saveOrder')->name('report.order.edit');
    
    // 
     Route::post('usersettings/store', 'UserSettingController@store')->name('usersettings.store');
    
     //Lectures
    Route::post('lectures/status','LectureController@status')->name('lectures.status');
    Route::post('lectures/delete','LectureController@deleteLecture')->name('lectures.deleteLecture');
    Route::get('lectures/view/{id}','LectureController@list')->name('lectures.list');
    Route::get('lectures/getemployees','LectureController@getEmployees')->name('lectures.getemployees');
    Route::post('lectures/markattendance','LectureController@markAttendance')->name('lectures.markattendance');
    
    //Resources
    Route::resource('userpermission', 'UserpermissionController');
    Route::resource('leave', 'LeaveController');
    Route::resource('lectures','LectureController');
    Route::resource('report', 'ReportController');
    Route::resource('late_entries', 'LateEntryController');
    Route::resource('entry', 'EntryController');
    Route::resource('compensation', 'CompensationController');
    Route::resource('holiday', 'HolidayController');
    Route::resource('usersettings', 'UserSettingController');
    Route::resource('evaluation', 'EvaluationController');
    Route::resource('skills', 'SkillController');
    Route::resource('idps', 'IDPController');
    
});

Route::prefix('trainee')->namespace('Trainee')->as('trainee.')->middleware(['role:trainee', 'web'])->group(function () {
     Route::get('dashboard', 'TraineeController@dashboard')->name('dashboard');
     Route::match(['get', 'post'], 'profile', 'TraineeController@myprofile')->name('profile');
     Route::match(['get', 'post'], 'changepassword', 'TraineeController@changepassword')->name('changepassword');
     
    //Polls
     Route::get('polls', ['uses' => 'TraineeController@polls'])->name('polls');
     Route::get('vote/{id}', ['uses' => 'TraineeController@vote'])->name('vote');
     
    Route::post('entry/timer/stop', 'EntryController@stop')->name('entry.stop');
    Route::get('entry/entryitems/{id}', 'EntryController@getentryitems')->name('entry.entryitems');
    Route::match(['get', 'post'], 'entry/timeronrequest', 'EntryController@timeronrequest')->name('entry.timeronrequest');
     
    //Entry routes
    Route::resource('entry', 'EntryController');
    Route::resource('late_entries', 'LateEntryController');

    //permission
    Route::resource('userpermission','UserpermissionController');
    Route::resource('leave','LeaveController');

    //Lectures
    Route::post('lectures/status','LectureController@status')->name('lectures.status');
    Route::post('lectures/delete','LectureController@deleteLecture')->name('lectures.deleteLecture');
    Route::get('lectures/view/{id}','LectureController@list')->name('lectures.list');
    Route::get('lectures/getemployees','LectureController@getEmployees')->name('lectures.getemployees');
    Route::post('lectures/markattendance','LectureController@markAttendance')->name('lectures.markattendance');
 
    //Report
    Route::post('report/reportitem/store', 'ReportController@storeReportitem')->name('report.storeReportitem');
    Route::put('report/reportitem/update/{id}', 'ReportController@updateReportitem')->name('report.updateReportitem');
    Route::get('report/searchproject', 'ReportController@searchProject')->name('report.searchproject');
    Route::post('report/searchtechnolgy', 'ReportController@searchTechnolgy')->name('report.searchtechnolgy');
    Route::get('report/getreportitems', 'ReportController@getReportitems')->name('report.getReportitems');
    Route::post('report/getreportitemform', 'ReportController@getReportitemForm')->name('report.getReportitemForm');
    Route::post('report/updatereportitems', 'ReportController@updateReportitems')->name('report.updateReportitems');
    Route::delete('report/deletereportitems','ReportController@deleteReportitems')->name('report.deleteReportitems');
    Route::post('report/timeronrequest', 'ReportController@timeronrequest')->name('report.timeronrequest');
    Route::match(['get','post'], 'report/releaselockbreak', 'ReportController@releaselockbreak')->name('report.releaselockbreak');
    Route::match(['get','post'], 'report/extendhours', 'ReportController@extendhours')->name('report.extendhours');
    // Route::get('report/monthlyreports/assessment', 'ReportController@getMonthlyAssessmentReport')->name('report.monthlyreports.assessment');
    // Route::get('report/monthlyreports/breaktimings', 'ReportController@getMonthlyBreaktimingsReport')->name('report.monthlyreports.breaktimings');
    // Route::get('report/monthlybreaks', 'ReportController@employeeMonthlyBreaks')->name('report.monthlybreaks');
  
    // Route::get('report/monthlyreports/project', 'ReportController@getMonthlyWorkingHoursReport')->name('report.monthlyreports.project');
    // Route::get('report/monthlyreports/showproject', 'ReportController@showMonthlyWorkingHoursReport')->name('report.monthlyreports.showproject');

    // Route::get('report/monthlyreports/dailyreport', 'ReportController@getDailyMonthlyReport')->name('report.monthlyreports.dailyreport');
    // Route::post('report/monthlyreports/getmonthlyreportitems', 'ReportController@getMonthlyReportItems')->name('report.monthlyreports.getmonthlyreportitems');
    // Route::get('report/monthlyreports/leavereport', 'ReportController@getLeaveMonthlyReport')->name('report.monthlyreports.leavereport');
    // Route::post('report/monthlyreports/getleaveitems', 'ReportController@getLeaveItems')->name('report.monthlyreports.getleaveitems');
    // Route::get('report/yearlyreports/leave', 'ReportController@getLeaveYearlyReport')->name('report.yearlyreports.leave');
    // Route::post('report/yearlyreports/getleaveitems', 'ReportController@getyearlyleaveitems')->name('report.yearlyreports.getleaveitems');
    // Route::post('report/setpermissiontime', 'ReportController@setPermissionTime')->name('report.setPermissionTime');
    Route::match(['get','post'], 'report/getgithubCommits', 'ReportController@getgithubCommits')->name('report.getgithubCommits');
    Route::match(['get','post'], 'report/createprojectname', 'ReportController@createprojectname')->name('report.createprojectname');
    Route::post('report/order/update','ReportController@saveOrder')->name('report.order.edit');
    Route::resource('report', 'ReportController');
    Route::resource('lectures','LectureController');
});

//Test pull request
