<?php

namespace App\Http\Controllers\api\adminDashboard;
use Exception;
use App\Models\Note;
use App\Models\User;
use App\Models\Store;
use App\Models\Theme;
use App\Mail\SendMail;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Homepage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\VerificationEvent;
use App\Http\Resources\NoteResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class StoreController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $success['stores'] =
       StoreResource::collection(Store::with(['categories' => function ($query) {
    $query->select('name','icon');
},'city' => function ($query) {
    $query->select('id');
},'country' => function ($query) {
    $query->select('id');
},'user' => function ($query) {
    $query->select('id');
}])->where('is_deleted', 0)->orderByDesc('created_at')->select('id','store_name','domain','status','periodtype','special','verification_status','verification_date','created_at')->get());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع المتاجر بنجاح', 'Stores return successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' =>  ['required', 'string','max:255', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0);
            })],

            'store_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0);
            })],
            'store_email' =>[ 'required','email',Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted',0);
            })],
             'password' => 'required|min:8|string',
            'domain' => ['required','string', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted',0);
            })],
            'userphonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users', 'phonenumber')->where(function ($query) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0);
            })],

            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('stores')->where(function ($query) {
                return $query->where('is_deleted',0);
            })],
            'activity_id' => 'required|array',
            'subcategory_id' => ['nullable', 'array'],
            //'package_id' =>'required',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_country_id' => 'required|exists:countries,id',
            'user_city_id' => 'required|exists:cities,id',
            //'periodtype' => 'nullable|required_unless:package_id,1|in:6months,year',
            'periodtype' => 'required|in:6months,year',
            'status' => 'required|in:active,inactive',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],

        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_name' => $request->user_name,
            'user_type' => "store",
            'password' => $request->password,
            'phonenumber' => $request->userphonenumber,
            'image' => $request->image,
            'country_id' => $request->user_country_id,
            'city_id' => $request->user_city_id,
            'status' => $request->status,
        ]);

        $userid = $user->id;

       $request->package_id = 2;
      $request->periodtype = "6months";

        $store = Store::create([
            'store_name' => $request->store_name,
            'store_email' => $request->store_email,
            'domain' => $request->domain,
            'icon' => $request->icon,
            'phonenumber' => $request->phonenumber,
            'description' => $request->description,
            'business_license' => $request->business_license,
            'ID_file' => $request->ID_file,
            'snapchat' => $request->snapchat,
            'facebook' => $request->facebook,
            'snapchat' => $request->snapchat,
            'twiter' => $request->twiter,
            'youtube' => $request->youtube,
            'instegram' => $request->instegram,
            'logo' => $request->logo,
            'entity_type' => $request->entity_type,
            'package_id' => $request->package_id,
            'user_id' => $userid,
            'periodtype' => $request->periodtype,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
        ]);
        $user->update([
            'store_id' => $store->id,
        ]);
        $user->assignRole("المالك");
        $Homepage = Homepage::create([
            'banar1' => 'banar.png',
            'banarstatus1' => 'active',
            'banar2' => 'banar.png',
            'banarstatus2' => 'active',
            'banar3' => 'banar.png',
            'banarstatus3' => 'active',
            'slider1' => 'slider.png',
            'sliderstatus1' => 'active',
            'slider2' => 'slider.png',
            'sliderstatus2' => 'active',
            'slider3' => 'slider.png',
            'sliderstatus3' => 'active',
            'store_id' => $store->id,
        ]);
        $theme = Theme::create([
            'store_id' => $store->id,
        ]);

        if ($request->periodtype == "6months") {
            $end_at = date('Y-m-d', strtotime("+ 6 months", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);

        } elseif ($request->periodtype == "year") {
            $end_at = date('Y-m-d', strtotime("+ 1 years", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);
        } else {
            $end_at = date('Y-m-d', strtotime("+ 2 weeks", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);

        }
        // if($request->package_id ==1){
        //   $end_at = date('Y-m-d', strtotime("+ 2 weeks", strtotime($store->created_at)));
        //   $store->update([
        //       'start_at' => $store->created_at,
        //       'end_at' => $end_at]);

        //  }
        // $store->activities()->attach($request->activity_id);
        if ($request->subcategory_id != null) {
            $subcategory = implode(',', $request->subcategory_id);
        } else {
            $subcategory = null;
        }

         $store->categories()->attach($request->activity_id,['subcategory_id' =>$subcategory] );
        $store->packages()->attach($request->package_id, ['start_at' => $store->created_at, 'end_at' => $end_at, 'periodtype' => $request->periodtype, 'packagecoupon_id' => $request->packagecoupon]);

        $success['stores'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة متجر بنجاح', ' store Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($store)
    {
        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجودة", "store is't exists");
        }

        $success['stores'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المتجر  بنجاح', 'store showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store)
    {

        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $user = $store->user;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'user_name' =>  ['required', 'string', Rule::unique('users')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0)
                    ->where('id', '!=', $store->user->id);
            })],
            'store_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0)
                    ->where('id', '!=', $store->user->id);
            })],
            'store_email' => 'required|email|unique:stores,store_email,' . $store->id,
            'password' => 'required|min:8|string',
            'domain' =>['required','string', Rule::unique('stores')->where(function ($query) use ($store) {
                return $query->where('is_deleted',0)->where('id', '!=',$store->id);
            })],
            'userphonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', Rule::unique('users', 'phonenumber')->where(function ($query) use ($store) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0)
                    ->where('id', '!=', $store->user->id);
            })],
            'phonenumber' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/', 'unique:stores,phonenumber,' . $store->id],
            // 'package_id' =>'required',
             'activity_id' => 'required|array',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'user_country_id' => 'required|exists:countries,id',
            'user_city_id' => 'required|exists:cities,id',
            'periodtype' => 'required|in:6months,year',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $request->package_id = 1;
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_name' => $request->input('user_name'),
            'password' => $request->input('password'),
            'phonenumber' => $request->input('phonenumber'),
            'image' => $request->input('image'),
            'country_id' => $request->input('user_country_id'),
            'city_id' => $request->input('user_city_id'),
        ]);

        $store->update([
            'store_name' => $request->input('store_name'),
            'store_email' => $request->input('store_email'),
            'domain' => $request->input('domain'),
            'icon' => $request->input('icon'),
            'description' => $request->input('description'),
            'business_license' => $request->input('business_license'),
            'ID_file' => $request->input('ID_file'),
            'snapchat' => $request->input('snapchat'),
            'facebook' => $request->input('facebook'),
            'snapchat' => $request->input('snapchat'),
            'twiter' => $request->input('twiter'),
            'youtube' => $request->input('youtube'),
            'instegram' => $request->input('instegram'),
            'logo' => $request->input('logo'),
            'entity_type' => $request->input('entity_type'),
            'package_id' => $request->input('package_id'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),
            'periodtype' => $request->input('periodtype'),
        ]);
        // $store->activities()->sync($request->activity_id);
        $store->categories()->sync($request->activity_id);

        if ($request->periodtype == "6months") {
            $end_at = date('Y-m-d', strtotime("+ 6 months", strtotime($store->created_at)));

            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);

        } else {
            $end_at = date('Y-m-d', strtotime("+1 years", strtotime($store->created_at)));
            $store->update([
                'start_at' => $store->created_at,
                'end_at' => $end_at]);
        }
        $store->packages()->sync($request->package_id, ['start_at' => $store->created_at, 'end_at' => $end_at, 'periodtype' => $request->periodtype, 'packagecoupon_id' => $request->packagecoupon]);

        $success['stores'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'store updated successfully');
    }

    public function updateProfile(Request $request, $store)
    {

        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $storeAdmain = User::where('user_type','store')->where('store_id', $store->id)->first();
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_name' =>  ['required', 'string', Rule::unique('users')->where(function ($query) use ($storeAdmain) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0)
                    ->where('id', '!=', $storeAdmain->id);
            })],
            'email' => ['required', 'email', Rule::unique('users')->where(function ($query) use ($storeAdmain) {
                return $query->whereIn('user_type', ['store', 'store_employee'])->where('is_deleted',0)
                    ->where('id', '!=', $storeAdmain->id);
            })],
            'password' => 'required|min:8|string',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $storeAdmain->update([
            'email' => $request->input('email'),
            'user_name' => $request->input('user_name'),
            'password' => $request->input('password'),
        ]);

        $success['user'] = new UserResource($storeAdmain);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'user updated successfully');
    }

    public function changeStatus(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->get();
        foreach ($stores as $store) {
            if (is_null($store) || $store->is_deleted != 0) {
                return $this->sendError("المتجر غير موجود", " Store is't exists");
            }

            if ($store->status === 'active') {
                $store->update(['status' => 'not_active']);
            } else {
                $store->update(['status' => 'active']);
            }

        }

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المتجر بنجاح', 'store updated successfully');

    }

    public function specialStatus($id)
    {
        $store = Store::query()->find($id);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }

        if ($store->special === 'not_special') {
            $store->update(['special' => 'special']);
        } else {
            $store->update(['special' => 'not_special']);
        }
        $success['store'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة المتجر بنجاح', 'store updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */

    public function changeSatusall(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {
                if ($store->status === 'active') {
                    $store->update(['status' => 'not_active']);
                    $users = User::where('store_id', $store->id)->get();
                    if($users !== null){
                    foreach ($users as $user) {
                        $user->update(['status' => 'not_active']);
                    }
                   }
                } else {
                    $store->update(['status' => 'active']);
                    $users = User::where('store_id', $store->id)->get();
                    if($users !== null){
                    foreach ($users as $user) {
                        $user->update(['status' => 'active']);
                            $data1= [
                            'subject' => "قبول التسجيل",
                            'message' => "تم قبول التسجيل",
                            'store_id' => $store->id,
                        ];
                //              try {
                //  Mail::to($user->email)->send(new SendMail($data1));
                //  } catch (Exception $e) {
                //   return $this->sendError('صيغة البريد الالكتروني غير صحيحة', 'The email format is incorrect.');
                //           }
                    }
                   }
                }
                $success['stores'] = new StoreResource($store);

            }
            $success['status'] = 200;
          return $this->sendResponse($success, 'تم تعديل الحالة بنجاح', 'store updated successfully');

        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

    public function destroy($store)
    {
        $store = Store::query()->find($store);
        if (is_null($store) || $store->is_deleted != 0) {
            return $this->sendError("المتجر غير موجود", "store is't exists");
        }
        $store->update(['is_deleted' => $store->id]);
        $users = User::where('store_id', $store->id)->get();
        foreach ($users as $user) {
            $user->update(['is_deleted' => $user->id]);
            $comment = Comment::where('comment_for', 'store')->where('user_id', $user->id)->where('is_deleted', 0)->first();
            if ($comment != null) {
                $comment->update(['is_deleted' => $comment->id]);
            }
        }
        $products=Product::where('store_id', $store->id)->get();
        foreach ($products as $product) {
            $product->update(['is_deleted' => $product->id]);
        }

        $success['store'] = new StoreResource($store);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف المتجر بنجاح', 'store deleted successfully');
    }

    public function addNote(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'subject' => 'required|string|max:255',
            'details' => 'required|string',
            'store_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $note = Note::create([
            'subject' => $request->subject,
            'details' => $request->details,
            'store_id' => $request->store_id,
            'product_id' => null,
        ]);

        $success['notes'] = new NoteResource($note);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة ملاحظة بنجاح', 'note Added successfully');
    }

    // public function verification_update(Request $request)
    // {
    //     $store = Store::query()->find($request->store_id);
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //        'activity_id' => 'required|array',
    //         'store_name' => 'required|string',
    //         'link' => 'required|url',
    //         'file' => 'required|mimes:pdf,doc,excel',
    //         'name' => 'required|string|max:255',
    //         'store_id' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }

    //     $users = User::where('store_id', $request->store_id)->where('user_type', 'store')->get();

    //     $data = [
    //         'message' => 'تعديل توثيق',
    //         'store_id' => $request->store_id,
    //         'user_id' => auth()->user()->id,
    //         'type' => "store_verified",
    //         'object_id' => $request->store_id,
    //     ];
    //     foreach ($users as $user) {
    //         Notification::send($user, new verificationNotification($data));
    //     }
    //     event(new VerificationEvent($data));
    //     $store->update([
    //         'store_name' => $request->input('store_name'),
    //         'link' => $request->input('link'),
    //         'file' => $request->input('file'),

    //     ]);

    //     // $store->activities()->sync($request->activity_id);
    //     $store->categories()->sync($request->activity_id);
    //     $user = User::where('is_deleted', 0)->where('store_id', $request->store_id)->where('user_type', 'store')->first();
    //     $user->update([
    //         'name' => $request->input('name'),
    //     ]);

    //     $success['store'] = Store::where('is_deleted', 0)->where('id', $request->store_id)->first();
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم تعديل المتجر بنجاح', 'store update successfully');
    // }
    public function deleteall(Request $request)
    {

        $stores = Store::whereIn('id', $request->id)->where('is_deleted', 0)->get();
        if (count($stores) > 0) {
            foreach ($stores as $store) {

                $store->update(['is_deleted' => $store->id]);
                $users = User::where('store_id', $store->id)->get();
                foreach ($users as $user) {
                    $user->update(['is_deleted' => $user->id]);
                }
            }
            $success['stores'] = StoreResource::collection($stores);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المتجر بنجاح', 'store deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }

    }

}
