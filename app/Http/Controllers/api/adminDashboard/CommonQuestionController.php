<?php

namespace App\Http\Controllers\api\adminDashboard;

use Illuminate\Http\Request;
use App\Models\CommonQuestion;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonQuestionResource;
use App\Http\Controllers\api\BaseController as BaseController;

class CommonQuestionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=CommonQuestion::where('is_deleted',0);
        $data= $data->paginate($count);
        $success['commonQuestions']=CommonQuestionResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        $success['status']= 200;

         return $this->sendResponse($success,'تم ارجاع الاسئلة بنجاح','Questions return successfully');
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'question' =>  ['required','string','max:255'],
            'answer' =>  ['required','string','max:255'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }

        $CommonQuestion = CommonQuestion::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);


        $success['common_question'] = new CommonQuestionResource($CommonQuestion);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم الاضافة بنجاح', 'Added successfully');
    }
    public function show($question)
    {
        $common_question = CommonQuestion::find($question);

        if (is_null($common_question)) {
            return $this->sendError("الاسئلة الشائعة غير موجودة", " Common Question is't exists");
        }

        $success['common_question'] = new CommonQuestionResource($common_question);
        $success['status'] = 200;
        return $this->sendResponse($success,'تم ارجاع الاسئلة بنجاح','Questions return successfully');


    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'question' =>  ['required','string','max:255'],
            'answer' =>  ['required','string','max:255'],

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $question = CommonQuestion::find($id);

        if (is_null($question)) {
            return $this->sendError("الاسئلة الشائعة غير موجود", " Common Questions is't exists");
        }
        $question->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);



        $success['common_question'] = new CommonQuestionResource($question);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'updated successfully');
    }
    public function deleteAll(Request $request)
    {

        $questions = CommonQuestion::whereIn('id', $request->id)->get();

        if (count($questions) > 0) {
            foreach ($questions as $question) {

                $question->delete();
            }
        }
        $success['commonQuestions']=CommonQuestionResource::collection($questions);

        $success['status'] = 200;

        return $this->sendResponse($success, 'تم الحذف بنجاح', 'deleted successfully');
    }
}
