<?php
namespace App\interfaces;
interface SocialiteInterface{
    public function getRedirectUrl($provider);
    public function createUser($user,$provider);
    public function getSocialUserDetails($provider);
}






?>