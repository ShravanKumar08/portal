<?php

namespace App\Models;

use App\Notifications\MailResetPasswordToken;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Inani\Larapoll\Traits\Voter;
use Spatie\Permission\Traits\HasRoles;
use Validator;
use App\Helpers\CustomfieldHelper;
use function redirect;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    protected $table = 'users';
    public $timestamps = true;
    public $incrementing = false;

    use Notifiable, Uuids, HasRoles, SoftDeletes, Voter, Impersonate;

    const DEFAULT_PASSWORD = 123456;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'isTeamLead'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class) ?? new Employee();
    }

    public function getEmployeeIdAttribute()
    {
        return @$this->employee->id;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }

    public static function getChangePasswordRules()
    {
        return [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!(Hash::check($value, \Auth::user()->password))) {
                        $fail('Your current password does not matches with the password you provided. Please try again');
                    }
                },
            ],
            'new_password' => [
                'required',
                'min:3',
                function ($attribute, $value, $fail) {
                    if (strcmp($value, request()->get('current_password')) == 0) {
                        $fail('New Password cannot be same as your current password. Please choose a different password.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (strcmp($value, User::DEFAULT_PASSWORD) == 0) {
                        $fail('New Password cannot be same as default password. Please choose a different password.');
                    }
                },
            ],
            'confirm_password' => ['required', 'min:1', 'same:new_password'],
        ];
    }

    public function saveChangepassword($request)
    {
        $this->password = bcrypt($request->get('new_password'));
        $this->save();
    }

    public function routeNotificationForSlack() {
        return env('LOG_SLACK_WEBHOOK_URL');
    }
}
