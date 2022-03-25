<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\CaseLog;
use App\CaseCloseContact;
use App\CaseNurseNotifier;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

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
                            if($item['contact_user_id']){
                                $close_contact['case_id'] = $save->id;
                                $close_contact['user_id'] = Auth::user()->id;
                                $close_contact['contact_user_id'] = $item['contact_user_id'];
                                CaseCloseContact::create($close_contact);
                            }
                        }
                    }
                    
                    
                    $cases = Cases::where('id',$save->id)->with('case_logs')->first();
                    
                    $admin_emails = CaseNurseNotifier::all();
                    if($admin_emails){
                        foreach($admin_emails as $admin_email){
                            $email = $admin_email['email'];
                            if($admin_email['location'] == $request->location){
                                $message_to_admin = "<p> We have a <strong>new</strong> case.</p>
                                                        <hr>
                                                        <ul>
                                                            <li>Date : ".$request->case_date."</li>
                                                            <li>Employee Name : ".$name."</li>
                                                            <li>Department / Company : ".$department . ' / ' . $company."</li>
                                                            <li>Temperature : ".$request->temperature."</li>
                                                            <li>Initial Findings : ".$request->initial_findings."</li>
                                                            <li>Remarks : ".$request->remarks."</li>
                                                            <li>Location : ".$request->location."</li>
                                                        </ul>
                                                        <p>Link : https://myvisitors.lafilgroup.com:8671/dashboard</p>
                                                        ";

                                $send_webex_to_admin = $this->sendWebexMessage($email,$message_to_admin);
                            }
                        }
                    }

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


    public function sendWebexMessage($email,$message){

        $httpClient = new Client(); 

        if($email && $message){
            $body = [
                'toPersonEmail' => $email,
                'html' => $message
            ];

            try{
                $response = $httpClient->post(
                    'https://api.ciscospark.com/v1/messages',
                    [
                        RequestOptions::BODY => json_encode($body),
                        RequestOptions::HEADERS => [
                            'Content-Type' => 'application/json',
                            //prod
                            'Authorization' => 'Bearer NWNiZjI4YTgtOGViZS00NGMxLWFhMTUtOGU3YTdjOWZjMWQ4ODc0Mjg0YmUtNjUy_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
                            
                            //dev tester
                            // 'Authorization' => 'Bearer YjViNGU3YTgtNTk1Ni00OGI3LWE4M2YtYmQxMTJhM2Q5YmEwOWNhMGExNzQtYjA1_PF84_72c16376-f5a4-4a5c-ad51-a60a7b78a790',
                        ],
                    ]
                );

            return 'sent';

            }catch(ServerException $e){
                return 'not';
            }
            catch(RequestException $e){
                return 'not';
            }
            catch(ConnectException $e){
                return 'not';
            }
            catch(ClientException $e){
                return 'not';
            }

        }
    }


}
