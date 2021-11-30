<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\forgotPassword;
use App\Http\Requests\LoginRequest;
use App\services\socialiteService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\resetPassword;
use App\Models\User;
use App\services\RegisterService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
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
            return response()->error(403,'Email Already exist in the database');
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
                $token=$user->createToken('auth_token')->plainTextToken;
                return response()->success(['token'=>$token],'Email Is not verified please verify to continue',203);
            }
            //check if the email is matching the credentials
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->error(403,'Email Or password Donot match');
            }
            $token=$user->createToken('auth_token')->plainTextToken;
            return response()->success(['user'=>$user,'token'=>$token]);

    
        }

//logout

public function logout(){

    Auth::guard('web')->logout();
    return response()->success(null,'User Logged Out successfully',200);

}

//forgot password
public function forgotPassword(forgotPassword $request){
    $user = (new RegisterService())->CheckAlreadyExist($request->email);
    if(!$user){
        return response()->error(401,'Invalid email adress is provided');
    }
    //send reset email 
    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? response()->success(null,'Password reset link is send to your mail successfully',200)
                : response()->error(500,'Something Went wrong Please Try Again!');
}

//reset password

public function reset(resetPassword $request){

    $status = Password::reset(
        $request->only('email', 'password','password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
                return  $user;
            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? response()->success(null,'Password reseted successfully Please Login',200)
                : response()->error(500,'Password Reset link is invalid or expired');
}

}
