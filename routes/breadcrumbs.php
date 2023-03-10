<?php
use Illuminate\Support\Str;

// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

//Admin Routes
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

Breadcrumbs::register("myprofile", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('My Profile');
});

Breadcrumbs::register("setting.generate_payslip", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Payslip', route('setting.generate_payslip'));
});

Breadcrumbs::register("report.monthlyreports.project", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Project Monthly Report');
});


Breadcrumbs::register("project.mergeproject", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Merge Projects');
});
//End

//Employee Routes
Breadcrumbs::register("employee.dashboard", function ($breadcrumbs) {
    $breadcrumbs->push('Home');
});

Breadcrumbs::register("employee.access", function ($breadcrumbs) {
    $breadcrumbs->parent("employee.index");
    $breadcrumbs->push('Access');
});

Breadcrumbs::register("employee.profile", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('My Profile');
});

Breadcrumbs::register("employee.polls", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Polls', route('employee.polls'));
});

Breadcrumbs::register("employee.composemail", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Contact', route('employee.composemail'));
});

Breadcrumbs::register("employee.vote", function ($breadcrumbs) {
    $breadcrumbs->parent("employee.polls");
    $breadcrumbs->push('Vote');
});

Breadcrumbs::register("report.monthlyreports.assessment", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Monthly Assessment');
});


Breadcrumbs::register("report.monthlyreports.dailyreport", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Daily Monthly Reports');
});

Breadcrumbs::register("report.monthlyreports.leavereport", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Leave Monthly Reports');
});

Breadcrumbs::register("report.yearlyreports.leave", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Yearly Leaves');
});

Breadcrumbs::register("employee.report.monthlyreports.assessment", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Monthly Assessment');
});

Breadcrumbs::register("employee.report.monthlyreports.breaktimings", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Break Timings');
});

Breadcrumbs::register("employee.report.myreports", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('My Reports');
});

Breadcrumbs::register("employee.report.viewreport", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('View Report');
});

Breadcrumbs::register("employee.report.monthlyreports.dailyreport", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Daily Monthly Reports');
});

Breadcrumbs::register("employee.report.monthlyreports.leavereport", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Leave Reports');
});

Breadcrumbs::register("employee.report.yearlyreports.leave", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Yearly Leaves');
});

Breadcrumbs::register("employee.holiday", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Holiday');
});

Breadcrumbs::register("employee.upcoming_birthday", function ($breadcrumbs) {
    $breadcrumbs->parent("employee.index");
    $breadcrumbs->push('upcoming birthday');
});

Breadcrumbs::register("employee.break_timings", function ($breadcrumbs) {
    $breadcrumbs->parent("employee.index");
    $breadcrumbs->push('Break Timings');
});

Breadcrumbs::register("employee.mailoverride", function ($breadcrumbs) {
    $breadcrumbs->parent("employee.index");
    $breadcrumbs->push('mailoverride ');
});

Breadcrumbs::register("employee.report.monthlyreports.project", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Project Monthly Report');
});

//End

//Trainee

Breadcrumbs::register("employee.trainee_breaktimings", function ($breadcrumbs) {
    $breadcrumbs->parent("employee.index");
    $breadcrumbs->push('Break Timings');
});

Breadcrumbs::register("trainee.report.timeronrequest", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Report Create');
});

Breadcrumbs::register("trainee.profile", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('My Profile');
});

Breadcrumbs::register("trainee.entry.timeronrequest", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Timer On Request');
});

Breadcrumbs::register("trainee.polls", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Polls', route('trainee.polls'));
});

//End

// Resource routes for both admin and employee
$admin_routes = [
    'employee', 'setting', 'project', 'technology', 'holiday', 'designation', 'userpermission', 'customfield', 'leave',
    'tempcard','lectures',
    'entry', 'officetiming', 'late_entries', 'compensation', 'report', 'officetimingslot', 'platform', 'grade', 'question', "permission","interviewstatus","interviewcall" ,"interviewprescreening", "schedule", "assesment"
];
$employee_routes = ['employee.userpermission','employee.lectures' , 'employee.leave', 'employee.report', 'employee.entry', 'employee.late_entries', 'employee.compensation', 'employee.holiday', 'employee.usersettings', 'employee.mailoverride' ,'employee.evaluation'];
$trainee_routes = ['trainee.entry', 'trainee.userpermission', 'trainee.leave','trainee.late_entries','trainee.report','trainee.lectures'];

$resources = array_merge($admin_routes, $employee_routes, $trainee_routes);

foreach ($resources as $resource) {
    Breadcrumbs::register("$resource.index", function ($breadcrumbs) use ($resource) {
        $breadcrumbs->parent('home');
        $title = Str::title(str_replace(['employee.', 'trainee.'], ['', ''], $resource));
        $breadcrumbs->push($title, route("$resource.index"));
    });

    Breadcrumbs::register("$resource.create", function ($breadcrumbs) use ($resource) {
        $breadcrumbs->parent("$resource.index");
        $breadcrumbs->push('Create');
    });

    Breadcrumbs::register("$resource.edit", function ($breadcrumbs) use ($resource) {
        $breadcrumbs->parent("$resource.index");
        $breadcrumbs->push('Edit');
    });

    Breadcrumbs::register("$resource.show", function ($breadcrumbs) use ($resource) {
        $breadcrumbs->parent("$resource.index");
        $breadcrumbs->push('View');
    });
}
//End


//Poll Routes

Breadcrumbs::register("poll.index", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Polls', route("poll.index"));
});

Breadcrumbs::register("poll.create", function ($breadcrumbs) {
    $breadcrumbs->parent("poll.index");
    $breadcrumbs->push('Create Poll');
});

Breadcrumbs::register("poll.edit", function ($breadcrumbs) {
    $breadcrumbs->parent("poll.index");
    $breadcrumbs->push('Edit Poll');
});

Breadcrumbs::register("poll.options.push", function ($breadcrumbs) {
    $breadcrumbs->parent("poll.index");
    $breadcrumbs->push('Add Options');
});

Breadcrumbs::register("poll.options.remove", function ($breadcrumbs) {
    $breadcrumbs->parent("poll.index");
    $breadcrumbs->push('Remove Options');
});

Breadcrumbs::register("admin.env.index", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('env');
});

Breadcrumbs::register("question.generate", function ($breadcrumbs) {
    $breadcrumbs->parent("home");
    $breadcrumbs->push('Generate Q/A');
});

//End

