<?php

namespace App\Http\Controllers\api;

use DB;
use App\Models\City;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Country;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Activity;
use App\Models\Shippingtype;
use App\Http\Resources\CartResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\ShippingCitiesResource;
use App\Http\Controllers\api\BaseController as BaseController;

class SelectorController extends BaseController
{

    public function cities()
    {
        $success['cities'] = CityResource::collection(City::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المدن بنجاح', 'cities return successfully');
    }

    public function countries()
    {
        $success['countries'] = CountryResource::collection(Country::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الدول بنجاح', 'countries return successfully');
    }

    public function activities()
    {
        $success['activities'] = ActivityResource::collection(Activity::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الأنشطة بنجاح', 'activities return successfully');
    }

    public function packages()
    {
        $success['packages'] = PackageResource::collection(Package::where('is_deleted', 0)->where('status', 'active')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الباقات بنجاح', 'packages return successfully');
    }

    public function addToCart()
    {

        $success['contents'] = CartResource::collection(DB::table('shoppingcart')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الباقات بنجاح', 'packages return successfully');
    }

    public function getAllCity()
    {

        $curl = curl_init();
        $key = array(
            'userId' => env('GOTEX_UserId_KEY'),
            'apiKey' => env('GOTEX_API_KEY'),
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/saee/get-cities',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($key),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $success['cities'] = json_decode($response);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إرجاع المدن', ' cities successfully');
    }
    public function testgetAllCity()
    {
        $curl = curl_init();

        $test = curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dashboard.go-tex.net/gotex-co-test/saee/get-cities',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"userId":"651d6aa9caff20c31d7404fc",
"apiKey":"AkUJTmkHrEHPWc08xNTzc9dd2QtI7t6vCM@m!CZuIrv7#J#k6P7$j@JL0gVNVOPH1tV8VMfuRRdgf$fq4DuJcDmiYlfGkQ2Xp$RUK3@w@IRmqnEMdg@!O7jpcXOG!7eO4wSKwE8@LhDtvr3SzSJR6P"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        dd($test);
        $response = curl_exec($curl);

        if ($response === false) {
            return "CURL Error: " . curl_error($curl);
        }
        curl_close($curl);

        $success['cities'] = json_decode($response);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إرجاع المدن', ' cities successfully');
    }
    public function shippingcities($id)
    {  
        //if shipping is another
        if($id == 5){
            $id=4;
        }
        $shippingCompany = Shippingtype::query()->find($id);
        $success['cities'] =  $shippingCompany !== null ? ShippingCitiesResource::collection($shippingCompany->shippingcities()->where('status', 'active')->get()) : array();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المدن بنجاح', 'city return successfully');

    }
    public function activateAccount($id)
    {
        $user = User::where('id', $id)->first();

        if (is_null($user) || $user->is_deleted != 0) {
            return $this->sendError("المستخدم غير موجودة", "user is't exists");
        }
        if ($user->status === 'not_active') {
            $user->update(['status' => 'active']);
        }
        $success['users'] = new UserResource($user);
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تفعيل المستخدم بنجاح', 'user status updated successfully');

    }

  public function registrationMarketer(){
    $success['registration_marketer'] = Setting::orderBy('id', 'desc')->pluck('registration_marketer')->first();
    $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض حالة المندوب بنجاح', 'registration_marketer show successfully');

  }
  public function try()
  {
    //   $role = Role::create(['name'=>"productEntry" , 'type'=>'admin' ,'guard_name'=>'api']);
    //   $arr=array();
    //   for ($i = 3; $i <= 25; $i++) {
    //       $arr[]=$i;
    //   }
     
    //   $role->syncPermissions($arr);
    //   $user = User::create([
    //       'name' => "مدخل منتجات",
    //       'user_name' => "Entery",
    //       'user_type' => 'admin_employee',
    //       'email' => "a@gmail.com",
    //       'password' => "1234567",
    //       'phonenumber' => "+966502356892",
    //       // 'image' => $request->image,
    //       'verified' => 1,

    //   ]);
   
    //   $user->assignRole($role->name);
    // role_has_permissions::create([])
    $role = Role::where('id',2)->first();
    $permissions=array();
for($i=103 ;$i<221;$i++){
    $permissions[]=$i;
}
    $role->givePermissionTo($permissions);
      $success['status'] = 200;
    //   $success['object'] =  $result;
      return $this->sendResponse($success, 'تم', 'user  successfully');

  }
}
