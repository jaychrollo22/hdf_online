<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cases;


class CaseLogsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        session([
            'title'=>'Case Logs'
        ]);

        return view('case_logs.index');
    }

    public function getAllCaseLogs(){
        $employee = session('employee');
        $name = $employee['first_name'] . ' ' . $employee['last_name'];
        return Cases::where('name',$name)->get();
    }


}
