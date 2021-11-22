<?php

namespace App\services;

use App\interfaces\RegisterInterface;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
class RegisterService implements RegisterInterface
{
    public function CheckAlreadyExist($email)
    {
        $user = User::where([
            ['email','=',$email],
            ['provider','=', null]
        ])->first();

        if($user){
            return true;
        }
        
    }

    public function createUser(RegisterRequest $request){
           $created = User::create([
            'name'=>$request->userName,
            'email'=>$request->email,
            'password'=>$request->password,
            'role'=>'user'
           ]);
           //send email verification    
           $created->sendEmailVerificationNotification();
           $token = $created->createToken('auth_token')->plainTextToken;
           return ['user'=>$created,'token'=>$token];
      

    }
    protected function makeToken($user){
        return $user->createToken('auth_token')->plainTextToken;
    }
}
