<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Resources\SectionResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class SectionController extends BaseController
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
        $success['Sections']=SectionResource::collection(Section::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع الاقسام بنجاح','Sections return successfully');
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show($section)
    {
        $section= Section::query()->find($section);
        if (is_null($section) || $section->is_deleted==1){
               return $this->sendError("القسم غير موجودة","Section is't exists");
               }
              $success['sections']=New SectionResource($section);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض القسم بنجاح','Section showed successfully');
    }
    public function changeStatus($id)
    {
        $section = Section::query()->find($id);
        if (is_null($section) || $section->is_deleted==1){
         return $this->sendError("القسم غير موجودة","Section is't exists");
         }
        if($section->status === 'active'){
            $section->update(['status' => 'not_active']);
     }
    else{
        $section->update(['status' => 'active']);
    }
        $success['sections']=New SectionResource($section);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة القسم بنجاح',' Section status updared successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    //     if (is_null($section) || $section->is_deleted==1){
    //         return $this->sendError("القسم غير موجودة"," section is't exists");
    //    }
            $input = $request->all();
        $validator =  Validator::make($input ,[
             'data' => 'required|array',
           'data.*.name' => 'required|string',
           'data.*.status' => 'required',
           'data.*.id' => 'nullable|numeric',

           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
         foreach ($request->data as $data) {
         $sections[] = Section::updateOrCreate([
        'id' => $data['id'],
      ], [
        'name' => $data['name'],
        'status' => $data['status']
      ]);
    }

           $success['sections']=SectionResource::collection($sections);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','section updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy( $section)
    {
        $section =Section::query()->find($section);
        if (is_null($section) || $section->is_deleted==1){
            return $this->sendError("القسم غير موجودة","section is't exists");
            }
           $section->update(['is_deleted' => 1]);

           $success['sections']=New SectionResource($section);
           $success['status']= 200;

            return $this->sendResponse($success,'تم حذف القسم بنجاح','section deleted successfully');
    }
}
