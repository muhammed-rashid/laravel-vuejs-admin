<?php

namespace App\services;
use App\interfaces\SocialiteInterface;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
class socialiteService implements SocialiteInterface{

public function getRedirectUrl($provider){
    return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
   
}
public function createUser($user,$provider){

 //check if this is already registered
 $newUser = User::firstOrCreate(
    [
        'email' => $user->getEmail(),'provider'=>'google' 
    ],
    [
        'name' => $user->getName(),
        'provider' => 'google',
        'email_verified_at' => now(),
    ]
);
    //user created need to generate a token
    $token = $newUser->createToken('auth_token')->plainTextToken;
    return $this->makeAuthenticationCookie([
        'userId'=>$newUser->id,
        'authToken'=>$token,
        'redirectUrl'=>'/#/'
    ]);
}

public function getSocialUserDetails($provider){
    $user = Socialite::driver($provider)->stateless()->user();
    if (!$user->token) {
        return ['error'=>'Unprocessable entity'];
    }

    return $this->createUser($user,$provider);
}
private function makeAuthenticationCookie($result)
    {
        $result['cookie'] = cookie('authentication',
            json_encode($result),
            8000,
            null,
            null,
            false,
            false
        );
        return $result;
    }
}



?>