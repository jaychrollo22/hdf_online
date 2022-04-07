<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QrHealthDeclarationForm;

use Auth;
use DB;
use QRCode;
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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session([
            'title'=>'Home'
        ]);
        return view('home');
    }

    public function indexData()
    {
        return QrHealthDeclarationForm::where('user_id',Auth::user()->id)
                                        ->where('expiry_date','>=',date('Y-m-d'))
                                        ->orderBy('created_at','DESC')
                                        
                                        ->first();
    }

    public function saveQrHealthDeclarationForm(Request $request){

        $this->validate($request, [
            'one_question' => 'required',
            'two_question' => 'required',
            'three_question' => 'required',
            'four_question' => 'required',
            'five_question' => 'required',
            'six_question' => 'required',
            'seven_question' => 'required',
            'eight_question' => 'required',
            'nineth_question' => 'required',
            'tenth_question' => 'required',
        ]);

        $data = $request->all();

        DB::beginTransaction();
        try{
            $employee = session('employee');
            $data['employee_id'] = $employee['id'];
            $data['user_id'] = Auth::user()->id;
            $data['name'] = Auth::user()->name;
            $data['dept_bu_position'] = $employee['cluster'] . ' ' . $employee['position'];
            $data['contact_number'] = $employee['contact_number'];
            $date_today = date('Y-m-d');
            $data['expiry_date'] = date('Y-m-d 23:59:00', strtotime($date_today . ' +1 day'));
            if($new = QrHealthDeclarationForm::create($data)){
                DB::commit();
                QRCode::text($new->id)->setSize(50)->setMargin(2)->setOutfile(base_path().'/public/hdf_qr/'. $new->id.'.png')->png();
                $new_qr_hdf = QrHealthDeclarationForm::where('id',$new->id)->first();
                return $response = [
                    'status' => 'saved',
                    'qr_hdf' => $new_qr_hdf
                ];
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }
}
