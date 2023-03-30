<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SettingsDataTable;
use App\Helpers\AppHelper;
use App\Helpers\CustomfieldHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Mail\PaySlip;
use App\Mail\BirthdayEmailWishes;
use App\Models\Holiday;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SettingsDataTable $dataTable)
    {
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Setting $setting)
    {
        $data['Model'] = $setting;
        $this->_append_values($data, $setting);
        return view('admin.settings.create_email_template', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Setting $setting, Request $request)
    {
        $this->_validate($request);
        $data = $request->except('_token');
        $data['emailtemplate'] = 1;
        $setting->fill($data);
        $setting->save();

        flash('Email Template created successfully')->success();
        return redirect()->route('setting.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Setting $setting)
    {
        $data['Model'] = $setting;
        $this->_append_values($data, $setting);

        return view('admin.settings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'value' => [function ($attribute, $value, $fail) use ($setting) {
                if ($setting->isMailsetting) {
                    $emails = array_filter(array_flatten(array_map(function ($item) {
                        return explode(',', $item);
                    }, $value)));

                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            return $fail('There are some invalid email addresses.');
                        }
                    }
                }
            }],
        ]);

        if ($request->hasFile('value')) {
            $file = $request->file('value');
            $setting->value = Storage::disk('public')->putFileAs('uploads', $file, strtolower($setting->name) . '.' . $file->getClientOriginalExtension());
        } else {
            $setting->value = $request->value;
            $setting->emailparams = $request->emailparams;
        }
        $setting->emailparams = @$request->emailparams ?: [];
        $setting->save();

        flash('Settings updated successfully')->success();
        $scope = $setting->emailtemplate == 1 ? ['scope' => 'emailtemplate'] : [];
        return redirect()->route('setting.index', $scope);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function _append_values(&$data, $setting)
    {
        if (in_array($setting->name, ['EMPLOYEE_ROLES', 'ADMIN_ROLES'])) {
            $data['selectvalues'] = Role::pluck('name', 'name')->toArray();
        }

        if (in_array($setting->name, ['EXCLUDE_EMPLOYEE_FROM_BREAKS', 'EXCLUDE_EMPLOYEE_FROM_REPORTS'])) {
            $data['selectvalues'] = Employee::active()->pluck('name', 'id')->toArray();
        }

        //        if($setting->isEmailTemplate){
        $data['Employees'] = AppHelper::getTableColumnsByName('employees');
        $data['EmployeeCustomfields'] = CustomfieldHelper::getCustomfieldsByModule(Employee::class)->pluck("name");
        //        }

        if ($setting->isMailsetting) {
            $data['Emails'] = User::pluck('email')->toArray();
        }

        if ($setting->name == 'OFFICIAL_PERMISSION_LEAVE_DAYS') {
            $data['numberFormatter'] = new \NumberFormatter('en_US', \NumberFormatter::ORDINAL);
        }

        if ($setting->name == 'EXCLUDE_EMPLOYEE_FROM_BREAKS') {
            $data['Employees'] = Employee::oldest('name')->active()->pluck("name", "id")->toArray();
        }
    }

    public function payslip(Request $request)
    {
        return view('admin.payslip');
    }

    public function searchUserEmail()
    {
        return response()->json(User::all());
    }

    private function _validate($request, $id = null)
    {
        $this->validate($request, [
            'name' => "required|unique:settings,name,$id,id,deleted_at,NULL",
        ]);
    }

    public function generatePayslip(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'month' => 'required',
                'toemployee' => 'required|email',
                'subject' => 'required',
                'mail_content' => 'required',
                'pdf_content' => 'required',
            ]);

            Mail::to($request->toemployee)->queue(new PaySlip($request->except('_token')));

            return response()->json(true);
        } else {
            $data['employees']          = Employee::oldest('name')->active()->get()->pluck("name", "user.email")->toArray();
            $data['email_templates']    = Setting::whereIn('name', ['PAYSLIP_CONTENT_MAIL', 'PAYSLIP_PDF_MAIL'])->get();
            $data['current_month']      = Carbon::now()->format('m-Y');
            $data['request']            = $request;

            return view('admin.settings.generate_payslip', $data);
        }
    }

    public function getEmployeePayslipForm(Request $request)
    {
        $this->validate($request, [
            "payslip.*" => 'nullable|numeric',           
        ]);
        $employee = Employee::whereHas('user', function ($q) use ($request) {
            $q->where('email', $request->toemployee);
        })->first();

        $settings = Setting::whereIn('name', ['PAYSLIP_CONTENT_MAIL', 'PAYSLIP_PDF_MAIL'])->get();

        $contents = [];
        
        foreach ($settings as $setting) {
            $content = Setting::strReplaceEmployeeContent($setting->value, $employee);
            
            foreach ($request->payslip as $key => $value) {
                $content = str_replace("{payslip.$key}", $value, $content);
            }

            $contents[$setting->name] = [
                'content' => $content,
                'subject' => Setting::strReplaceEmployeeContent($setting->emailparams['subject'], $employee),
            ];
        }

        return view('admin.settings.partials._payslip_form', compact('contents','request'));
    }

    public function emailpreview(Request $request)
    {
        $subject = 'Hearty Wishes from Team Technokryon!';
        $employee = Employee::query()->active()->first();
        $birthday_subject = $request->emailparams;
        $content = $request->email_content;
        if ($birthday_subject != '') {
            $subject = Setting::strReplaceEmployeeContent($birthday_subject, $employee);
        }
        $mail_content = Setting::strReplaceEmployeeContent($content, $employee);
        Mail::to($request->email_id)->queue(new BirthdayEmailWishes($mail_content, $subject));
    }

    public function calculatepayslip(Request $request)
    {
        $start = new DateTime(Carbon::parse('26-'.$request->month)->submonth()->format('Y-m-d'));
        $end = new DateTime('25-'.$request->month);

        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');

        $interval = $end->diff($start);

        // total days
        $days = $interval->days;

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one

        $holidays = Holiday::whereBetween('date', [date(Carbon::parse('26-'.$request->month)->submonth()->format('Y-m-d')), date(Carbon::parse('25-'.$request->month)->format('Y-m-d'))])
            ->pluck('date')->toArray();
        

        foreach($period as $dt) {
            $curr = $dt->format('D');

            // substract if Saturday or Sunday
            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
            }

            // (optional) for the updated question
            elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                $days--;
            }
        }

        $this->validate($request, [
            'month' => 'required',
            'toemployee' => 'required|email',
            'gross_pay' => 'required|numeric',
            'lop' => 'nullable|numeric',
            'tds' => 'nullable|numeric',
        ]);
        $gross_pay = $request['gross_pay'];

        $setting = Setting::where('name', ['PAYSLIP_CALCULATIONS'])->first();

        $data['gross_pay'] = $request['gross_pay'];
        $data['toemployee'] = $request['toemployee'];
        $data['tds'] = $request['tds'];
        $data['BP'] = $gross_pay * $setting['value']['BP']['value'] / 100;
        $data['DA'] = $gross_pay * $setting['value']['DA']['value'] / 100;
        $data['HRA'] =  $data['BP'] * $setting['value']['HRA']['value'] / 100;
        $sum_base_da = $data['BP'] + $data['DA'];
        $epf_max = $setting['value']['EPF_MAX']['value'];
        $epf_gross = $sum_base_da < $epf_max ? $sum_base_da : $epf_max;
        $data['EPF'] = $epf_gross * $setting['value']['EPF']['value'] / 100;
        $data['ESI_GP'] = 208;
        $data['special_allowance'] = $data['gross_pay'] - ($sum_base_da +  $data['HRA']);
        $data['leaves'] = $gross_pay / $days * $request['lop'];
        $data['total_deduction'] = $data['EPF'] + $data['ESI_GP'] + $data['leaves'] + $request['tds'];
        $data['net_pay'] = $gross_pay -   $data['total_deduction'];

        return view('admin.settings.partials.generate_payslip', $data);
    }
}
