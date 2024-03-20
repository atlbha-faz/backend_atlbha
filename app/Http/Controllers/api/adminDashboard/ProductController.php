<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Note;
use App\Models\User;
use App\Models\Store;
use App\Mail\SendMail;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Events\VerificationEvent;
use App\Http\Resources\NoteResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\ProductAdaminResource;
use App\Notifications\verificationNotification;
use App\Http\Controllers\api\BaseController as BaseController;

class ProductController extends BaseController
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
        {
            $success['products'] = ProductAdaminResource::collection(Product::with(['store' => function ($query) {
                $query->select('id', 'domain', 'store_name', 'store_email','logo','icon');
            }, 'category'])->where('is_deleted', 0)->where('for', 'store')->where('original_id',null)->orderByDesc('created_at')->select('id', 'name', 'status', 'cover', 'special', 'store_id', 'created_at', 'admin_special')->get());
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم ارجاع المنتجات بنجاح', 'products return successfully');
        }
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


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        $product = Product::query()->find($product);
        if (is_null($product) || $product->is_deleted != 0 || $product->for == 'etlobha') {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }
        $success['products'] = new ProductResource($product);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض المنتج بنجاح', 'product showed successfully');
    }

    public function specialStatusall(Request $request)
    {
        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'store')->get();

        if (count($products) > 0) {
            foreach ($products as $product) {

                if ($product->admin_special === 'not_special') {
                    $product->update(['admin_special' => 'special']);
                } else {
                    $product->update(['admin_special' => 'not_special']);
                }
                $success['product'] = new ProductResource($product);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'Product updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }

    }
    public function specialStatus($id)
    {
        $product = Product::where('id', $id)->where('is_deleted', 0)->where('for', 'store')->first();

        if (is_null($product) || $product->is_deleted != 0) {
            return $this->sendError("المنتج غير موجود", "product is't exists");
        }

        if ($product->admin_special === 'not_special') {
            $product->update(['admin_special' => 'special']);
        } else {
            $product->update(['admin_special' => 'not_special']);
        }
        $success['product'] = new ProductResource($product);

        $success['status'] = 200;
        return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'Product updated successfully');

    }

    public function changeSatusall(Request $request)
    {

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'store')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                if ($product->status === 'active') {
                    $product->update(['status' => 'not_active']);
                } else {
                    $product->update(['status' => 'active']);
                }
                $success['products'] = new ProductResource($product);

            }
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم تعديل حالة المنتج بنجاح', 'product updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }
    public function rateing($product)
    {
        $product = Product::where('is_deleted', 0)->where('id', $product)->first();
        if (is_null($product)) {
            return $this->sendError("المنتجات غير موجودة", " product is't exists");
        }
        $rating = $product->comment->avg('rateing');

        $success['rateing'] = $rating;
        $success['status'] = 200;
        return $this->sendResponse($success, 'تم عرض التقييم بنجاح', ' rateing showrd successfully');

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteall(Request $request)
    {

        $products = Product::whereIn('id', $request->id)->where('is_deleted', 0)->where('for', 'store')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {

                $product->update(['is_deleted' => $product->id]);
            }
            $success['products'] = ProductResource::collection($products);
            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف المنتج بنجاح', 'product deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
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
            'product_id' => $request->product_id,
        ]);
        $store = Store::query()->find($request->store_id);
        $data = [
            'message' => $request->details,
            'store_id' => $store->id,
            'user_id' => $store->user_id,
            'type' => $request->subject,
            'object_id' => $request->product_id,
        ];
        $user = User::query()->find($store->user_id);
        Notification::send($user, new verificationNotification($data));
        event(new VerificationEvent($data));
        Mail::to($user->email)->send(new SendMail($data));


        $success['notes'] = new NoteResource($note);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة ملاحظة بنجاح', 'note Added successfully');
    }
}
