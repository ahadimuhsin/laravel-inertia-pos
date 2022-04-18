<?php

namespace App\Http\Responses;
use Laravel\Fortify\Http\Responses\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return redirect('/login');
    }
}