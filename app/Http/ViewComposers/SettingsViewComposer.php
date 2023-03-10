<?php

namespace App\Http\ViewComposers;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class SettingsViewComposer {

    public $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function compose(View $view) {
        if(request()->hasSession()){
            $session = request()->session();
            // {Key -> $variable (you can use in view)} => {value -> Setting name}
            $setting_sessions = [
                'theme_color' => 'THEME_COLOR',
                'logo_light_icon' => 'LOGO_LIGHT_ICON',
                'logo_light_text' => 'LOGO_LIGHT_TEXT',
                'logo_dark_icon' => 'LOGO_DARK_ICON',
                'logo_dark_text' => 'LOGO_DARK_TEXT',
            ];

            foreach ($setting_sessions as $variable => $setting_session) {
                $value = $session->get("SETTINGS.$setting_session") ?? Setting::fetch($setting_session);
                $session->put("SETTINGS.$setting_session", $value);
                $view->with($variable, $value);
            }

            $route = request()->route()->getName();
            $view->with('row_title', $this->getRowTitle($route));
            $view->with('route_name', $route);

            $view->with('Version', trim(exec('git describe --tags --long')));
        }

        if(request()->route()){
            $prefix = @request()->route()->getPrefix();
            
            $view->with('is_admin_route', in_array($prefix, ['/admin', 'poll']));
            $view->with('is_employee_route', in_array($prefix, ['/employee']));
            $view->with('is_trainee_route', in_array($prefix, ['/trainee']));
        }else{
            $view->with('is_admin_route', false);
            $view->with('is_employee_route', false);
            $view->with('is_trainee_route', false);
        }
    }

    protected function getRowTitle($route)
    {
        if(request()->route()->getPrefix() == '/employee'){
            $route = str_replace('employee.', '', $route);
        }

        if(request()->route()->getPrefix() == '/trainee'){
            $route = str_replace('trainee.', '', $route);
        }

        $this->sanitizeTitle($route);

        return $route;
    }

    protected function sanitizeTitle(&$route)
    {
        $replace_titles = [
            'report.monthlyreports.dailyreport' => 'Monthly Report (Daily status)',
            'report.monthlyreports.assessment' => 'Monthly Assessment',
            'report.yearlyreports.leave' => 'Yearly Leave Report',
            'report.monthlyreports.leavereport' => 'Monthly Report (Leave)',
            'employee.access' => 'Make Super Admin',
            'userpermission.index' => 'Permission Track Record',
            "leave.index" => 'Leave Track Record',
            "leave.create" => 'Leave Application',
            "userpermission.create" => 'Permission Request',
            "employee.report.monthlyreports.project" => 'Monthly Report (Project)',
            "report.monthlyreports.project" => 'Monthly Report (Project)',
        ];
        
        $route = @$replace_titles[$route] ?: $route;

        $route = Str::title(str_replace(['.', '_'], [' ', ' '], $route));
    }
}
