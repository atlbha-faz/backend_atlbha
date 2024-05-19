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

class StoreDataController extends BaseController
{
    public function storeToken($id)
    {
        $store = Store::find($id);
        if ($store) {
            $user = User::find($store->user_id);
            $token = ($user) ? $user->createToken('authToken')->accessToken : '';
            Storage::disk('local')->put('tokens/swapToken.txt', $token);
            return $this->sendResponse(['token' => $token], 'تم انشاء توكين', 'token created');

        } else {
            return $this->sendError('المتجر غير موجود', 'store not found');

        }
    }

    public function getStoreToken()
    {
        $token = Storage::get('tokens/swapToken.txt');
        $user = PersonalAccessToken::findToken($token)->tokenable();


        return ['token' => $token, 'user' => $user];
    }
}
