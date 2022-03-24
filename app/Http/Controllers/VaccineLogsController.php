<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserVaccineLog;
use App\UserVaccinationDetail;

use DB;
use Auth;
use Storage;

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
            'title'=>'VACCINATION DETAILS'
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
            // 'dose' => 'required',
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
            // 'dose' => 'required',
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


    public function getUserVaccinationDetails(){
        $user_id = Auth::user()->id;
        return $user_vaccination_details = UserVaccinationDetail::where('user_id',$user_id)->first();
    }

    public function updateUserVaccinationDetails(Request $request){
        $user_id = Auth::user()->id;
        $data = $request->all();
        DB::beginTransaction();
        try{

            if(isset($request->attachment)){
                if($request->file('attachment')){
                    $attachment = $request->file('attachment');   
                    $filename = Auth::user()->id . '.' . $attachment->getClientOriginalExtension();
                    $path = Storage::disk('public')->putFileAs('vaccine_files', $attachment , $filename);
                    $data['attachment'] = $filename;
                }    
            }

            $data['user_id'] = $user_id;
            $data['is_approved'] = 'For Verification';
            //If has id
            if($data['id']){
                $user_vaccination_details = UserVaccinationDetail::where('id',$data['id'])->where('user_id',$user_id)->first();
                if($user_vaccination_details){
                    unset($data['id']);
                    $user_vaccination_details->update($data);
                    DB::commit();
                    $user_vaccination_details = UserVaccinationDetail::where('id',$user_vaccination_details->id)->first();
                    return $response = [
                        'status'=>'saved',
                        'user_vaccination_details'=>$user_vaccination_details
                    ];
                }else{
                    unset($data['id']);
                    UserVaccinationDetail::create($data);
                    DB::commit();
                    $user_vaccination_details = UserVaccinationDetail::where('user_id',$user_id)->first();
                    return $response = [
                        'status'=>'saved',
                        'user_vaccination_details'=>$user_vaccination_details
                    ];
                }
            }else{
                unset($data['id']);
                UserVaccinationDetail::create($data);
                DB::commit();
                $user_vaccination_details = UserVaccinationDetail::where('user_id',$user_id)->first();
                return $response = [
                    'status'=>'saved',
                    'user_vaccination_details'=>$user_vaccination_details
                ];
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }
}
