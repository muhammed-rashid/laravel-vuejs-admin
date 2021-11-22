<?php

namespace App\interfaces;
use App\Http\Requests\RegisterRequest;
interface RegisterInterface{
public function CheckAlreadyExist($email);
public function createUser(RegisterRequest $request);

}

?>