<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MadfuLoginRequest;
use App\Services\Madfu;
use Illuminate\Http\Request;

class MadfuController extends BaseController
{
    public function login(MadfuLoginRequest $request)
    {
        $username = 'wesam@faz-it.net';
        $password = 'Welcome@123';
        $login_request = (new Madfu())->login($username, $password, $request->uuid);
        if ($login_request->getStatusCode() == 200) {
            $login_request = json_decode($login_request->getBody()->getContents());
            if (!$login_request->status){
                return $this->sendError('',$login_request->message);
            }
            return $this->sendResponse(['token' => $login_request,
                'data' => $login_request], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }
    }
}
