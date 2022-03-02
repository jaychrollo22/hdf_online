<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Employee;
use App\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
class LoginController extends Controller
{
    /*Page Expired
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user) {
        $data = [
            'user_id'=>$user->id,
            'log_date'=>Carbon::now()->toDateTimeString(),
            'log_status'=>1
        ];
        UserLog::create($data);

        $employee = Employee::select('id','user_id','first_name','last_name','position','contact_number','cluster')
                                ->with('companies','departments')
                                ->where('user_id',$user->id)
                                ->first();
                                
        session([
            'employee'=>$employee
        ]);
    }

    public function logout(Request $request)
    {

        $data = [
            'user_id'=>Auth::user()->id,
            'log_date'=>Carbon::now()->toDateTimeString(),
            'log_status'=>0
        ];
        UserLog::create($data);

        $this->guard()->logout();

        

        return redirect('/login');
    }
}
