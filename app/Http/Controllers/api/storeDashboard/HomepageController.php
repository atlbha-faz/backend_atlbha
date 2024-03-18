<?php

namespace App\Http\Controllers\api\storeDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\HomepageResource;
use App\Http\Resources\ThemeResource;
use App\Models\Homepage;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function theme()
    {

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع الهوية بنجاح', 'Theme return successfully');
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
    //         'logo'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
    //         'banar1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
    //         'banarstatus1'=>'required|in:active,not_active',
    //         'banar2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
    //         'banarstatus2'=>'required|in:active,not_active',
    //         'banar3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
    //         'banarstatus3'=>'required|in:active,not_active',
    //         'clientstatus'=>'required|in:active,not_active',
    //         'commentstatus'=>'required|in:active,not_active',
    //         'slider1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
    //         'sliderstatus1'=>'required|in:active,not_active',
    //         'slider2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
    //         'sliderstatus2'=>'required|in:active,not_active',
    //         'slider3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
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
    //     if (is_null($Homepage) || $Homepage->is_deleted !=0){
    //            return $this->sendError("االصفحة غير موجودة","Homepage is't exists");
    //            }
    //           $success['homepages']=New HomepageResource($Homepage);
    //           $success['status']= 200;

    //            return $this->sendResponse($success,'تم عرض الصفحة بنجاح','Homepage showed successfully');
    // }
    // public function changeStatus($id)
    // {
    //     $Homepage = Homepage::query()->find($id);
    //     if (is_null($Homepage) || $Homepage->is_deleted !=0){
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
    //     if (is_null($homepage) || $homepage->is_deleted !=0){
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
    //         'logo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1048'],
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

    public function themePrimaryUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'primaryBg' => ['required'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([
                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'primaryBg' => $request->primaryBg,
        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeSecondaryUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [

            'secondaryBg' => ['required'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([

                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'secondaryBg' => $request->secondaryBg,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeHeaderUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [

            'headerBg' => ['required'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([
                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'headerBg' => $request->headerBg,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

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
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([
                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'layoutBg' => $request->layoutBg,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

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
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([

                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'iconsBg' => $request->iconsBg,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

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
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([
                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'productBorder' => $request->productBorder,
            'productBg' => $request->productBg,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());
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
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([
                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'filtersBorder' => $request->filtersBorder,
            'filtersBg' => $request->filtersBg,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function themeMainUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [

            'mainButtonBg' => ['required'],
            'mainButtonBorder' => ['required'],
            'subButtonBg' => ['required'],
            'subButtonBorder' => ['required'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([

                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'mainButtonBg' => $request->mainButtonBg,
            'mainButtonBorder' => $request->mainButtonBorder,
            'subButtonBg' => $request->subButtonBg,
            'subButtonBorder' => $request->subButtonBorder,
        ]);
        // $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    // public function themeSubUpdate(Request $request)
    // {

    //     $input = $request->all();
    //     $validator = Validator::make($input, [

    //     ]);
    //     if ($validator->fails()) {
    //         # code...
    //         return $this->sendError(null, $validator->errors());
    //     }
    //           $theme=Theme::where('store_id', auth()->user()->store_id)->first();
    //        $theme->update([

    //     ]);
    //     $homepage=Homepage::where('store_id', auth()->user()->store_id)->first();

    //     $success['homepages'] = new HomepageResource($homepage);
    //     $success['status'] = 200;

    //     return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    // }
    public function themeFooterUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [

            'footerBorder' => ['required'],
            'footerBg' => ['required'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([

                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'footerBorder' => $request->footerBorder,
            'footerBg' => $request->footerBg,
        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
   public function themeFontColorUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [

            'fontColor' => ['nullable'],


        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        if ($theme == null) {
            Theme::create([

                'primaryBg' => "#ffffff",
                'secondaryBg' => "#02466a",
                'headerBg' => "#1dbbbe",
                'layoutBg' => "#ffffff",
                'iconsBg' => "#1dbbbe",
                'productBorder' => "#ededed",
                'productBg' => "#ffffff",
                'filtersBorder' => "#f0f0f0",
                'filtersBg' => "#ffffff",
                'mainButtonBg' => "#1dbbbe",
                'mainButtonBorder' => "#1dbbbe",
                'subButtonBg' => "#02466a",
                'subButtonBorder' => "#02466a",
                'footerBorder' => "#ebebeb",
                'footerBg' => "#ffffff",
                'fontColor' =>"#000",
                'store_id' => auth()->user()->store_id,
            ]);
        }
        $theme = Theme::where('store_id', auth()->user()->store_id)->first();
        $theme->update([

            'fontColor' => $request->fontColor,

        ]);
        $homepage = Homepage::where('store_id', auth()->user()->store_id)->first();

        $success['Theme'] = new ThemeResource(Theme::where('store_id', auth()->user()->store_id)->first());

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'homepage updated successfully');
    }
    public function banarUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'banar1' => ['nullable','required_without_all:banar2,banar3' , 'max:1048'],
            'banarstatus1' => 'required|in:active,not_active',
            'banar2' => ['nullable', 'required_without_all:banar1,banar3' ,'max:1048'],
            'banarstatus2' => 'required|in:active,not_active',
            'banar3' => ['nullable','required_without_all:banar1,banar2' ,  'max:1048'],
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
            'slider1' => ['nullable', 'required_without_all:slider2,slider3', 'max:1048'],
            'sliderstatus1' => 'required|in:active,not_active',
            'slider2' => ['nullable','required_without_all:slider1,slider3' , 'max:1048'],
            'sliderstatus2' => 'required|in:active,not_active',
            'slider3' => ['nullable', 'required_without_all:slider1,slider2','max:1048'],
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
//     if (is_null($logohomepage) || $logohomepage->is_deleted !=0){
//         return $this->sendError("الصفحة غير موجودة"," homepage is't exists");
//    }
//         $input = $request->all();
//        $validator =  Validator::make($input ,[
//         'slider1'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
//         'sliderstatus1'=>'required|in:active,not_active',
//         'slider2'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
//         'sliderstatus2'=>'required|in:active,not_active',
//         'slider3'=>['required','image','mimes:jpeg,png,jpg,gif,svg','max:1048'],
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
