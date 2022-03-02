<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserVaccineLog;

use DB;
use Auth;

class VaccineLogsController extends Controller
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
            'title'=>'Vaccine Logs'
        ]);

        return view('vaccine_logs.index'); 
    }

    public function getAllVaccineLogs(Request $request){
        return UserVaccineLog::where('user_id',Auth::user()->id)->orderBy('date_given','DESC')->get();
    }

    public function saveVaccineLogs(Request $request){

        $this->validate($request, [
            'brand' => 'required',
            'date_given' => 'required',
            'dose' => 'required',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{

            $data['user_id'] = Auth::user()->id;

            if($save = UserVaccineLog::create($data)){
                DB::commit();
                $user_vaccine_log = UserVaccineLog::where('id',$save->id)->first();
                return $response = [
                    'status'=>'saved',
                    'user_vaccine_log'=>$user_vaccine_log
                ];
            }


        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }

    public function updateVaccineLogs(Request $request){

        $this->validate($request, [
            'brand' => 'required',
            'date_given' => 'required',
            'dose' => 'required',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{
            $user_vaccine_log = UserVaccineLog::where('id',$request->id)->first();
            if($user_vaccine_log){
                unset($data['id']);
                if($update = $user_vaccine_log->update($data)){
                    DB::commit();
                    $user_vaccine_log = UserVaccineLog::where('id',$user_vaccine_log->id)->first();
                    return $response = [
                        'status'=>'saved',
                        'user_vaccine_log'=>$user_vaccine_log
                    ];
                }
    
            }   

            

        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }
}
