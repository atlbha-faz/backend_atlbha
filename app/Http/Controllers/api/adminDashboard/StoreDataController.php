<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Token;
use Laravel\Sanctum\PersonalAccessToken;
use Lcobucci\JWT\Parser as JwtParser;


class StoreDataController extends BaseController
{
    public function storeToken($id)
    {
        $store = Store::find($id);
        if ($store) {
            $user = User::find($store->user_id);
            $token = ($user) ? $user->createToken('authToken')->accessToken : '';
            Storage::disk('local')->put('tokens/swapToken.txt', $token);
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
