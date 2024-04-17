<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MadfuLoginRequest;
use App\Services\Madfu;
use Illuminate\Http\Request;

class MadfuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function login(MadfuLoginRequest $request)
    {
        $username = 'Atlbha';
        $password = 'QU1NTAUNS1NXSSE';
        return (new Madfu())->login($username, $password, $request->uuid);
    }
}
