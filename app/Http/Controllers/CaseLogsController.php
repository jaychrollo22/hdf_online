<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cases;
use App\CaseLog;

use DB;
use Auth;
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
        return Cases::where('name',$name)->with('case_logs')->get();
    }

    public function saveCaseLogs(Request $request){

        $this->validate($request, [
            'log_date' => 'required',
            'temperature' => 'required',
            'oximeter' => 'required',
            'others' => 'required'
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{
            $data['user_id'] = Auth::user()->id;
            if($save = CaseLog::create($data)){
                DB::commit();
                $cases = Cases::where('id',$data['cases_id'])->with('case_logs')->first();
                return $response = [
                    'status'=>'saved',
                    'cases'=>$cases
                ];
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }

    public function updateCaseLogs(Request $request){

        $this->validate($request, [
            'log_date' => 'required',
            'temperature' => 'required',
            'oximeter' => 'required',
            'others' => 'required'
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{
          
            $case_log = CaseLog::where('id',$data['id'])->first();
            if($case_log){
                if($case_log->update($data)){
                    DB::commit();
                    $cases = Cases::where('id',$data['cases_id'])->with('case_logs')->first();
                    return $response = [
                        'status'=>'saved',
                        'cases'=>$cases
                    ];  
                }
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }

    public function removeCaseLogs(Request $request){
        
        $data = $request->all();

        DB::beginTransaction();
        try{
            $case_log = CaseLog::where('id',$data['id'])->first();       
            if($case_log->delete()){
                DB::commit();
                $cases = Cases::where('id',$data['cases_id'])->with('case_logs')->first();
                return $response = [
                    'status'=>'removed',
                    'cases'=>$cases
                ];  
            }

        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }   

}
