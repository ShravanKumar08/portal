<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['backup_download']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function impersonateTakeRedirect(Request $request)
    {
        if($notfication = $request->session()->get('flash_notification')){
            $request->session()->flash('flash_notification', $notfication);
        }

        if(auth()->user()->hasRole('employee')){
            return redirect('employee/dashboard');
        }

        if(auth()->user()->hasRole('trainee')){
            return redirect('trainee/dashboard');
        }

        return redirect('/admin/dashboard');
    }

    public function forceDownload(Request $request, $export)
    {
        if($export == 'excel'){
            return response()->download(public_path($request->filename))->deleteFileAfterSend(true);
        }elseif ($export == 'pdf'){
            $snappy = app('snappy.pdf.wrapper');

            $options     = Config::get('datatables.snappy.options', [
                'no-outline'    => true,
                'margin-left'   => '0',
                'margin-right'  => '0',
                'margin-top'    => '10mm',
                'margin-bottom' => '10mm',
            ]);
            $orientation = Config::get('datatables.snappy.orientation', 'landscape');

            $snappy->setOptions($options)
                ->setOrientation($orientation);

            $content = Storage::disk('public')->get($request->filename);
            Storage::disk('public')->delete($request->filename);

            return $snappy->loadView('vendor.datatables.pdf', compact('content'))
                ->download('export_'.time().'.pdf');
        }
    }

    public function backup_download(Request $request)
    {
        try{
            return response()->download(storage_path('app/'.$request->path));
        }catch (\Exception $e){
            abort(403, 'File not exists');
        }
    }
}
