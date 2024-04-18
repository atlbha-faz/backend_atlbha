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
        $username = 'Atlbha';
        $password = 'QU1NTAUNS1NXSSE';
        $login_request = (new Madfu())->login($username, $password, $request->uuid);
        if ($login_request->getStatusCode() == 200) {
            return $this->sendResponse(['token' => json_decode($login_request->getBody()->getContents())->token,
                'data' => json_decode($login_request->getBody()->getContents())], 'عملية ناجحة', 'Success process');
        } else {
            return $this->sendError('خطأ في العملية', 'process failed');
        }
    }
}
