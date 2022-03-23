<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\UserRtPcrLog;

use DB;
use Auth;
use Storage;


class RtPcrLogsController extends Controller
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
            'title'=>'TEST RESULTS'
        ]);

        return view('rt_pcr_logs.index');
    }

    public function getAllRtPcrLogs(Request $request){
        return UserRtPcrLog::where('user_id',Auth::user()->id)->orderBy('rt_pcr_date','DESC')->get();
    }

    public function saveRtPcrLogs(Request $request){

        $this->validate($request, [
            'rt_pcr_date' => 'required',
            'results' => 'required',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{

            if(isset($request->rt_pcr_file)){
                if($request->file('rt_pcr_file')){
                    $attachment = $request->file('rt_pcr_file');   
                    $filename = Auth::user()->id . '_' . date('Ymd',strtotime($request->rt_pcr_date)) . '.' . $attachment->getClientOriginalExtension();
                    $path = Storage::disk('public')->putFileAs('rt_pcr_file', $attachment , $filename);
                    $data['rt_pcr_file'] = $filename;
                }    
            }

            $data['user_id'] = Auth::user()->id;

            if($save = UserRtPcrLog::create($data)){
                DB::commit();
                $user_rt_pcr_log = UserRtPcrLog::where('id',$save->id)->first();
                return $response = [
                    'status'=>'saved',
                    'user_rt_pcr_log'=>$user_rt_pcr_log
                ];
            }


        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }

    public function updateRtPcrLogs(Request $request){

        $this->validate($request, [
            'rt_pcr_date' => 'required',
            'results' => 'required',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{

            if(isset($request->rt_pcr_file)){
                if($request->file('rt_pcr_file')){
                    $attachment = $request->file('rt_pcr_file');   
                    $filename = Auth::user()->id . '_' . date('Ymd',strtotime($request->rt_pcr_date)) . '.' . $attachment->getClientOriginalExtension();
                    $path = Storage::disk('public')->putFileAs('rt_pcr_file', $attachment , $filename);
                    $data['rt_pcr_file'] = $filename;
                }    
            }
            
            $user_rt_pcr_log = UserRtPcrLog::where('id',$request->id)->first();
            if($user_rt_pcr_log){
                unset($data['id']);
                if($update = $user_rt_pcr_log->update($data)){
                    DB::commit();
                    $user_rt_pcr_log = UserRtPcrLog::where('id',$user_rt_pcr_log->id)->first();
                    return $response = [
                        'status'=>'saved',
                        'user_rt_pcr_log'=>$user_rt_pcr_log
                    ];
                }
    
            }   

            

        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }
}
