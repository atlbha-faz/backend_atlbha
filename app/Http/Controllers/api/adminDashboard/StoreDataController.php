<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\User;
use App\Models\Store;
use App\Models\UserLog;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lcobucci\JWT\Parser as JwtParser;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\api\BaseController;


class StoreDataController extends BaseController
{
    public function storeToken($id, Request $request)
    {
        $store = Store::find($id);
        if ($store) {
            $user = User::find($store->user_id);
            $token = ($user) ? $user->createToken('authToken')->accessToken : '';
            Storage::disk('local')->put('tokens/swapToken.txt', $token);
            UserLog::create([
                'user_id' => $store->user_id,
                'action' => 'login from admin dashboard',
                'ip_address' => $request->ip(), 
                'user_agent' => $request->userAgent(),
                'platform' =>($store->store_name) ? $store->store_name : $store->id,
            ]);
            $success['token'] = $token;
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم انشاء توكين', 'token created');

        } else {
            return $this->sendError('المتجر غير موجود', 'store not found');

        }
    }

    public function getStoreToken()
    {
        $token = Storage::get('tokens/swapToken.txt');
        if (!$token) {
            return ['token' => $token, 'user' => null];

        }
        $user = app(JwtParser::class)->parse($token)->claims()->get('jti');
        $user = User::with(['store' => function ($q) {
            return $q->select('id', 'logo', 'domain');
        }])->where('id', (Token::with([])->find($user))->user_id)->select('store_id', 'name', 'lastname', 'user_name', 'image')->first();
        $success['token'] = $token;
        $success['user'] = $user;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض توكين', 'token showed');
    }
}
