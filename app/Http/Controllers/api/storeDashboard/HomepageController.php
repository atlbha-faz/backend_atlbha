<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Models\Theme;
use App\Models\Homepage;
use Illuminate\Http\Request;
use App\Http\Resources\HomepageResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class HomepageController extends BaseController
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
       
        $success['Homepages'] = HomepageResource::collection(Homepage::where('is_deleted', 0)->where('store_id', auth()->user()->store_id)->get());
        // $success['Theme'] = Theme::where('store_id', auth()->user()->store_id)->get();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الصفحة الرئبسبة  بنجاح', 'Homepages return successfully');
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
    // public function store(Request $request)
    // {
    //     $input = $request->all();
    //     $validator =  Validator::make($input ,[
    //         'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'banar1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'banarstatus1'=>'required|in:active,not_active',
    //         'banar2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'banarstatus2'=>'required|in:active,not_active',
    //         'banar3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'banarstatus3'=>'required|in:active,not_active',
    //         'clientstatus'=>'required|in:active,not_active',
    //         'commentstatus'=>'required|in:active,not_active',
    //         'slider1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'sliderstatus1'=>'required|in:active,not_active',
    //         'slider2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'sliderstatus2'=>'required|in:active,not_active',
    //         'slider3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
    //         'sliderstatus3'=>'required|in:active,not_active',
    //         // 'store_id'=>'required|exists:stores,id',
    //     ]);
    //     if ($validator->fails())
    //     {
    //         return $this->sendError(null,$validator->errors());
    //     }

    //     $Homepage = Homepage::updateOrCreate([
    //             'store_id'=> auth()->user()->store_id,
    //             ],[
    //            'logo' => $request->logo,
    //            'banar1' => $request->banar1,
    //            'banarstatus1' => $request->banarstatus1,
    //            'banar2' => $request->banar2,
    //            'banarstatus2' => $request->banarstatus2,
    //            'banar3' => $request->banar3,
    //            'banarstatus3' => $request->banarstatus3,
    //            'clientstatus' => $request->clientstatus,
    //            'commentstatus' => $request->commentstatus,
    //            'slider1' => $request->slider1,
    //            'sliderstatus1' => $request->sliderstatus1,
    //            'slider2' => $request->slider2,
    //            'sliderstatus2' => $request->sliderstatus2,
    //            'slider3' => $request->slider3,
    //            'sliderstatus3' => $request->sliderstatus3,
    //         //    'store_id' => $request->input('store_id'),
    //        ]);

    //      $success['Homepages']=New HomepageResource($Homepage );
    //     $success['status']= 200;

    //      return $this->sendResponse($success,'تم إضافةالصفحة بنجاح','Homepage Added successfully');
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    // public function show($homepage)
    // {
    //     $Homepage= Homepage::query()->find($homepage);
    //     if (is_null($Homepage) || $Homepage->is_deleted==1){
    //            return $this->sendError("االصفحة غير موجودة","Homepage is't exists");
    //            }
    //           $success['homepages']=New HomepageResource($Homepage);
    //           $success['status']= 200;

    //            return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Homepage showed successfully');
    // }
    // public function changeStatus($id)
    // {
    //     $Homepage = Homepage::query()->find($id);
    //     if (is_null($Homepage) || $Homepage->is_deleted==1){
    //      return $this->sendError("الصفحة غير موجودة","Homepage is't exists");
    //      }
    //     if($Homepage->status === 'active'){
    //         $Homepage->update(['status' => 'not_active']);
    //  }
    //     else{
    //     $Homepage->update(['status' => 'active']);
    // }
    //     $success['homepages']=New HomepageResource($Homepage);
    //     $success['status']= 200;
    //      return $this->sendResponse($success,'تم تعدبل حالة الصفحة بنجاح',' Homepage status updared successfully');

    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    public function edit(Homepage $homepage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Homepage  $homepage
     * @return \Illuminate\Http\Response
     */
    // public function destroy($homepage)
    // {
    //     $homepage =Homepage::query()->find($homepage);
    //     if (is_null($homepage) || $homepage->is_deleted==1){
    //         return $this->sendError("الصفحة غير موجودة","Homepage is't exists");
    //         }
    //        $homepage->update(['is_deleted' => 1]);

    //        $success['homepages']=New HomepageResource($homepage);
    //        $success['status']= 200;

    //         return $this->sendResponse($success,'تم حذف الصفحة بنجاح','Homepage deleted successfully');
    // }

    // //////////////////////////////////////
    // public function logoUpdate(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendError(null, $validator->errors());
    //     }
    //     $logohomepage = Homepage::updateOrCreate([
    //         'store_id' => auth()->user()->store_id,
    //     ], [
    //         'logo' => $request->logo,
    //     ]);

    //     $success['homepages'] = new HomepageResource($logohomepage);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    // }

    public function themeSearchUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'searchBorder' => ['required'],
            'searchBg' => ['required'],
          
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
            'searchBorder' => $request->searchBorder,
            'searchBg' =>$request->searchBg,
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeCategoriesUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
        
            'categoriesBg' => ['required'],
           
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
           
            'categoriesBg' => $request->categoriesBg,
            
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeMenuUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
          
            'menuBg' => ['required'],
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
           
            'menuBg' => $request->menuBg,
            
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeLayoutUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
         
            'layoutBg' => ['required'],
           
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
       
            'layoutBg' => $request->layoutBg,
            
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeIconUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
     
            'iconsBg' => ['required'],
            
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
       
            'iconsBg' =>$request->iconsBg,
           
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeProductUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
          
            'productBorder' => ['required'],
            'productBg' => ['required'],
        
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
           
            'productBorder' => $request->productBorder,
            'productBg' =>$request->productBg
           
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
  
 
    public function themeFilterUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
           
            'filtersBorder' => ['required'],
            'filtersBg' => ['required'],
          
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
      
            'filtersBorder' => $request->filtersBorder,
            'filtersBg' => $request->filtersBg,
           
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeMainUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
         
            'mainButtonBg' => ['required'],
            'mainButtonBorder' => ['required'],
           
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
         
            'mainButtonBg' => $request->mainButtonBg,
            'mainButtonBorder' => $request->mainButtonBorder
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeSubUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
      
            'subButtonBg' => ['required'],
            'subButtonBorder' => ['required'],
         
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
        
            'subButtonBg' => $request->subButtonBg,
            'subButtonBorder' => $request->subButtonBorder,
          
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeFooterUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
      
            'footerBorder' => ['required'],
            'footerBg' => ['required']
          
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
              $theme=Theme::where('store_id', auth()->user()->store_id)->first();
           $theme->update([
     
            'footerBorder' => $request->footerBorder,
            'footerBg' => $request->footerBg
        ]);
        $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();
 
        $success['homepages'] = new HomepageResource($homepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }

    public function banarUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'banar1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'banarstatus1' => 'required|in:active,not_active',
            'banar2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'banarstatus2' => 'required|in:active,not_active',
            'banar3' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'banarstatus3' => 'required|in:active,not_active',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $banarhomepage = Homepage::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [
            'banar1' => $request->banar1,
            'banarstatus1' => $request->banarstatus1,
            'banar2' => $request->banar2,
            'banarstatus2' => $request->banarstatus2,
            'banar3' => $request->banar3,
            'banarstatus3' => $request->banarstatus3,
        ]);

        $success['homepages'] = new HomepageResource($banarhomepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }

    public function sliderUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'slider1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'sliderstatus1' => 'required|in:active,not_active',
            'slider2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'sliderstatus2' => 'required|in:active,not_active',
            'slider3' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'sliderstatus3' => 'required|in:active,not_active',
        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $banarhomepage = Homepage::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [
            'slider1' => $request->slider1,
            'sliderstatus1' => $request->sliderstatus1,
            'slider2' => $request->slider2,
            'sliderstatus2' => $request->sliderstatus2,
            'slider3' => $request->slider3,
            'sliderstatus3' => $request->sliderstatus3,
        ]);

        $success['homepages'] = new HomepageResource($banarhomepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }

// public function sliderUpdate(Request $request)
// {
//     $logohomepage = Homepage::where('store_id',auth()->user()->store_id)->first();
//     if (is_null($logohomepage) || $logohomepage->is_deleted==1){
//         return $this->sendError("الصفحة غير موجودة"," homepage is't exists");
//    }
//         $input = $request->all();
//        $validator =  Validator::make($input ,[
//         'slider1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
//         'sliderstatus1'=>'required|in:active,not_active',
//         'slider2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
//         'sliderstatus2'=>'required|in:active,not_active',
//         'slider3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
//         'sliderstatus3'=>'required|in:active,not_active',
//           ]);
//        if ($validator->fails())
//        {
//            # code...
//            return $this->sendError(null,$validator->errors());
//        }

//      $logohomepage->updateOrCreate([
//             'store_id'   => auth()->user()->store_id,
//             ],[
//                 'slider1' => $request->slider1,
//                 'sliderstatus1' => $request->sliderstatus1,
//                 'slider2' => $request->slider2,
//                 'sliderstatus2' => $request->sliderstatus2,
//                 'slider3' => $request->slider3,
//                 'sliderstatus3' => $request->sliderstatus3,
//               ]);

//        $success['homepages']=New HomepageResource($logohomepage);
//        $success['status']= 200;

//         return $this->sendResponse($success,'تم التعديل بنجاح','homepage updated successfully');
// }
    public function commentUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'commentstatus' => 'required|in:active,not_active',
            // 'clientstatus' => 'required|in:active,not_active',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $commenthomepage = Homepage::updateOrCreate([
            'store_id' => auth()->user()->store_id,
        ], [
            'commentstatus' => $request->commentstatus,
            // 'clientstatus' => $request->clientstatus,
        ]);

        $success['homepages'] = new HomepageResource($commenthomepage);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
}
