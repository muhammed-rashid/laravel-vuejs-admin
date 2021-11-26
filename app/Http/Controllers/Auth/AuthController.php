<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\services\socialiteService;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\services\RegisterService;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AuthController extends Controller
{
    protected $redirectTo = '/home';
    public $socialiteService;
    public function __construct(socialiteService $socialiteService)
    {
        $this->middleware('throttle:600,1')->only('verify', 'resend');
        $this->socialiteService = $socialiteService;
    }


    public function Redirect()
    {
        $url = $this->socialiteService->getRedirectUrl('google');
        return response()->success(['URL' => $url]);
    }

    public function GoogleAuth()
    {
      $result = $this->socialiteService->getSocialUserDetails('google');
     
    return redirect($result['redirectUrl'])->withCookie($result['cookie']);
     
    }

    //email and password register
    public function Register(RegisterRequest $request){
        
        if((new RegisterService())->CheckAlreadyExist($request->email)){
            return response()->error(403,'Email Already Exist');
        }
        //create a user
        $result = (new RegisterService())->createUser($request);
        return response()->success($result,'New user Created successfully,Please verify the account to continue');
           
    }

    //resend email verification link
    public function resend(Request $request)
    {
       

        if ($request->user()->hasVerifiedEmail()) {
            return response()->error(422,'User already have verified email!');
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->success(null,'The notification has been resubmitted');

    }
    //verify email adress

    public function verify($id,Request $request){

        $user = User::find($id);
   
        if ($user->email_verified_at != null) {
            return response()->error(401,'User is already verified');
          }

        $user->email_verified_at = Carbon::now();
        $user->remember_token = null;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->success(['user'=>$user,'token'=>$token],'Email Verified Successfully');
    }


    //login related actions

        public function Login(LoginRequest $request){
            $user = (new RegisterService())->CheckAlreadyExist($request->email);
            if(!$user){
                return response()->error(401,'There is no user in this email adress');
            }
            //check if email is verified
            if($user->email_verified_at == null){
                return response()->error(403,'Email Is not verified please verify to continue');
            }
            //check if the email is matching the credentials
            if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->success(['token'=>$token,'user'=>$user],'Login Successfull');
               
            }
    
            return response()->error(403,'Email Or password Donot match');
    
    
        }




}
