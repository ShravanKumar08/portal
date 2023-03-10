<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Entry;
use App\Models\Entryitem;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Report;
use App\Models\User;
use App\Models\Userpermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inani\Larapoll\Poll;
use function redirect;
use function response;
use function view;
use App\Helpers\AppHelper;

class UserController extends Controller {

    public function dashboard() {
        return view('admin.dashboard');
    }

    public function myprofile(Request $request) {
        $Model = \Auth::user();

        if ($request->post()) {
            $userdata = $request->only(['name', 'email', 'password']);

            if (!empty($userdata['password'])) {
                $userdata['password'] = bcrypt($userdata['password']);
            } else {
                unset($userdata['password']);
            }
            $Model->fill($userdata);
            $Model->save();

            flash('updated successfully')->success();
            return redirect('admin/dashboard');
        }

        return view('admin.users.my_profile', compact('Model'));
    }
    public function calendar_events(Request $request) {
       $eventsValue = AppHelper::calendarDashboard($request);
       return response()->json($eventsValue);
    }

    public function active(Request $request, $id) {
        $model = User::find($id);
        $model->active = $request->active;
        $model->save();
        return response()->json(['success' => 'Success!', 'active' => $model->active], 200);
    }
    
    public function viewVotes($id)
    {        
        $poll = Poll::findOrFail($id);
        $results = $poll->results()->grab();
        $votes = $poll->votes->pluck('option_id', 'user_id')->toArray();        
        
        return view('larapoll::dashboard.viewvote', compact('results', 'votes', 'poll'));
    }

}
