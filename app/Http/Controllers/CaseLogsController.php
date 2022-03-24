<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\CaseLog;
use App\CaseCloseContact;

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

    public function saveNewCase(Request $request){

        $employee = session('employee');
        $name = $employee['first_name'] . ' ' . $employee['last_name'];
        $department = $employee['departments'][0]['name'];
        $company = $employee['companies'][0]['name'];
        $contact_number = $employee['contact_number'];

        $data = $request->all();
        
        $case_close_contacts = json_decode($data['case_close_contacts'],true);

        DB::beginTransaction();
        try{
            $validate_case = Cases::where('case_date',$data['case_date'])->where('name',$name)->first();
            if(empty($validate_case)){
                
                $data['name'] = $name;
                $data['employee_id'] = $employee['id'];
                $data['user_id'] = Auth::user()->id;
                $data['department_company'] = $department . ' / ' . $company;
                $data['contact_number'] = $contact_number;
                unset($data['case_close_contacts']);
                if($save = Cases::create($data)){
                    DB::commit();

                    if(count($case_close_contacts) > 0){
                        foreach($case_close_contacts as $item){
                            $close_contact['case_id'] = $save->id;
                            $close_contact['user_id'] = Auth::user()->id;
                            $close_contact['contact_user_id'] = $item['contact_user_id'];
                            CaseCloseContact::create($close_contact);
                        }
                    }
                    
                    
                    $cases = Cases::where('id',$save->id)->with('case_logs')->first();
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

    public function getAllCaseLogs(){
        $employee = session('employee');
        $name = $employee['first_name'] . ' ' . $employee['last_name'];
        return Cases::where('name',$name)->with('case_logs')->orderBy('case_date','DESC')->get();
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

    public function getAllUsers(){
        return User::wherehas('employee',function($q){
                        $q->where('status','Active');
                    })
                    ->select('id','name')
                    ->orderBy('name','ASC')
                    ->get();
    }

}
