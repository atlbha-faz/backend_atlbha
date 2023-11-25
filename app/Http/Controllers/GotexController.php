<?php

namespace App\Http\Controllers;

use Spatie\Url\Url;
use Illuminate\Support\Facades\Http;
class GotexController extends Controller
{
    public $api_key;
    public $userId;
    public $base_url;

    public function __construct()
    {
        $this->api_key = config('app.GOTEX_API_KEY');
        $this->userId = config('app.GOTEX_UserId_KEY');
        $this->base_url = Url::fromString('https://dashboard.go-tex.net');

    }
    public function printSticker($id)
    {
        $url = $this->base_url->withPath(str('/gotex-co-test/imile/print-sticker/{id}')->replace('{id}', $id)->toString());
        // return $this->api_key;
        $content = [
            'userId' => $this->userId,
            'apiKey'=> $this->api_key,
        ];
        return Http::asJson()->withHeaders([
            'Content-Length' => strlen(json_encode($content)),
            'Host'=>'127.0.0.1'
            ])->withOptions([
                'verify'=>false  ])
            ->post($url,$content);
        // return http::get('https://google.com');
        // $url->__toString();
    }

}
