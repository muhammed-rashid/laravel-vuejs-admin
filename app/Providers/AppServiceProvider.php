<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success',function($data=null,$message=null,$status=200){
            return response()->json([
                'success'=>true,
                'message'=>$message,
                'data'=>$data
                
            ],$status);
        });

        Response::macro('error',function($status,$error){
            return response()->json([
                'success'=>false,
                'message'=>$error,     
            ],$status);
        });
    }
}
