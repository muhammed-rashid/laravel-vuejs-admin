<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\services\socialiteService;
use App\Http\Requests\RegisterRequest;
use App\services\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
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
      if(isset($result['error'])){
       return response()->error(422,$result['error']);
       }
    return redirect($result['redirectUrl'])->withCookie($result['cookie']);
     
    }

    //email and password register
    public function Register(RegisterRequest $request){

        if((new RegisterService())->CheckAlreadyExist($request->email)){
            return response()->error(403,'Email Already Exist');
        }
        //create a user
        $result = (new RegisterService())->createUser($request);
        return response()->success($result,'New user Created successfully');
           
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

    public function verify(Request $request){

       
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(
              [
                "message" => "Your'r email already verified.",
              ],
             
            );
          }
      
          if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
          }
      
          return response()->json(
            [
              "message" => "Verification complete thank you.",
            ],
           
          );
    }





}
