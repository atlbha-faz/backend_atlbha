<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Resources\TemplateResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\api\BaseController as BaseController;

class TemplateController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $success['templates']=TemplateResource::collection(Template::where('is_deleted',0)->get());
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع جميع القوالب بنجاح','templates return successfully');
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

        if($request->parent_id == null)
        {

            $input = $request->all();
            $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',

            ]);
            if ($validator->fails())
            {
                return $this->sendError(null,$validator->errors());
            }



            $template =Template::create([
                'name' => $request->name,
                'parent_id'=>$request->parent_id,
              ]);
        }
        else{

            $input = $request->all();
            $validator =  Validator::make($input ,[
                'name'=>'required|string|max:255',

                'parent_id'=>'required'
            ]);
            if ($validator->fails())
            {
                return $this->sendError(null,$validator->errors());
            }
            $template =Template::create([
                'name' => $request->name,
                'parent_id'=>$request->parent_id,
              ]);


    }
    $success['templates']=New TemplateResource($template);
    $success['status']= 200;

     return $this->sendResponse($success,'تم إضافة قالب بنجاح','Template Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show($template)
    {
        $template= Template::query()->find($template);
        if (is_null($template) || $template->is_deleted==1){
               return $this->sendError("القالب غير موجود","template is't exists");
               }
              $success['categories']=New templateResource($template);
              $success['status']= 200;

               return $this->sendResponse($success,'تم عرض القالب بنجاح','template showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
     {
        if (is_null($template) || $template->is_deleted==1){
            return $this->sendError("التصنيف غير موجودة"," template is't exists");
       }
        if($request->parent_id == null){

            $input = $request->all();
           $validator =  Validator::make($input ,[
            'name'=>'required|string|max:255',


           ]);
           if ($validator->fails())
           {
               # code...
               return $this->sendError(null,$validator->errors());
           }
           $template->update([
               'name' => $request->input('name'),
               'parent_id' => $request->input('parent_id'),

           ]);
           }
           else{
            $input = $request->all();
            $validator =  Validator::make($input ,[
             'name'=>'required|string|max:255',

             'parent_id'=>'required'

            ]);
            if ($validator->fails())
            {
                # code...
                return $this->sendError(null,$validator->errors());
            }
            $template->update([
                'name' => $request->input('name'),

                'parent_id' =>$request->input('parent_id'),

            ]);
           }

           $success['templates']=New templateResource($template);
           $success['status']= 200;

            return $this->sendResponse($success,'تم التعديل بنجاح','template updated successfully');
    }

  public function changeStatus($id)
    {
        $template = Template::query()->find($id);
        if (is_null($template) || $template->is_deleted==1){
         return $this->sendError("القسم غير موجودة","template is't exists");
         }
        if($template->status === 'active'){
            $template->update(['status' => 'not_active']);
     }
    else{
        $template->update(['status' => 'active']);
    }
        $success['templates']=New TemplateResource($template);
        $success['status']= 200;
         return $this->sendResponse($success,'تم تعدبل حالة القالب بنجاح',' template status updared successfully');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy($template)
    {
        $template =Template::query()->find($template);
        if (is_null($template) || $template->is_deleted==1){
            return $this->sendError("القالب غير موجودة","template is't exists");
            }
           $template->update(['is_deleted' => 1]);

           $success['templates']=New TemplateResource($template);
           $success['status']= 200;
            return $this->sendResponse($success,'تم حذف القالب بنجاح','template deleted successfully');
    }
}