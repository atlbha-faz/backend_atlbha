<?php

namespace App\Http\Controllers\api\adminDashboard;

use App\Http\Controllers\api\BaseController as BaseController;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageController extends BaseController
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
    public function index(Request $request)
    { 
        $count = ($request->has('number') && $request->input('number') !== null) ? $request->input('number') : 10;
        $data=Page::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])->where('is_deleted', 0)->where('store_id', null)->orderByDesc('created_at')->select('id', 'title', 'status', 'user_id', 'created_at');
        $data= $data->paginate($count);
        $success['pages'] = PageResource::collection($data);
        $success['page_count'] =  $data->lastPage();
        $success['current_page'] =  $data->currentPage();
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم ارجاع  الصفحة بنجاح', 'Pages return successfully');
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
            'title' => 'required|string|max:26',
            'page_desc' => 'required',
            'page_content' => 'required',
            'seo_title' => 'nullable',
            'seo_link' => 'nullable',
            'seo_desc' => 'nullable',
            'tags' => 'nullable',
            'altImage' => 'nullable',
            'pageCategory' => ['required', 'array'],
            'pageCategory.*' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $validator2 = Validator::make($input, [
            'postcategory_id' => Rule::requiredIf(function () {
                return in_array('1', request('pageCategory'));
            }),
            'image' => Rule::requiredIf(function () {
                return in_array('1', request('pageCategory'));
            }),

        ]);
        if ($validator2->fails()) {
            return $this->sendError(null, $validator2->errors());
        }

        $page = Page::create([
            'title' => $request->title,
            'page_content' => $request->page_content,
            'page_desc' => $request->page_desc,
            'seo_title' => $request->seo_title,
            'seo_link' => $request->seo_link,
            'seo_desc' => $request->seo_desc,
            'tags' => $request->tags,
            'user_id' => auth()->user()->id,
            'status' => 'not_active',
        ]);

        if ($request->pageCategory) {
            $page->page_categories()->attach($request->pageCategory);
            if (in_array(1, $request->pageCategory)) {
                $page->update([
                    'image' => $request->image,
                    'postcategory_id' => $request->postcategory_id,
                    'altImage' => $request->altImage,
                ]);
            }
        }
        $success['Pages'] = new PageResource($page);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة  الصفحة بنجاح', 'page_category Added successfully');
    }

    public function publish(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:26',
            'page_content' => 'required',
            'page_desc' => 'required',
            'seo_title' => 'nullable',
            'seo_link' => 'nullable',
            'seo_desc' => 'nullable',
            'tags' => 'nullable',
            'altImage' => 'nullable',
            'pageCategory' => ['required', 'array'],
            'pageCategory.*' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors());
        }
        $validator2 = Validator::make($input, [
            'postcategory_id' => Rule::requiredIf(function () {
                return in_array('1', request('pageCategory'));
            }),
            'image' => Rule::requiredIf(function () {
                return in_array('1', request('pageCategory'));
            }),

        ]);
        if ($validator2->fails()) {
            return $this->sendError(null, $validator2->errors());
        }

        $page = Page::create([
            'title' => $request->title,
            'page_content' => $request->page_content,
            'page_desc' => $request->page_desc,
            'seo_title' => $request->seo_title,
            'seo_link' => $request->seo_link,
            'seo_desc' => $request->seo_desc,
            'tags' => $request->tags,
            'user_id' => auth()->user()->id,
            'status' => 'active',
        ]);
        if ($request->pageCategory) {
            $page->page_categories()->attach($request->pageCategory);
            if (in_array(1, $request->pageCategory)) {
                $page->update([
                    'image' => $request->image,
                    'postcategory_id' => $request->postcategory_id,
                    'altImage' => $request->altImage,
                ]);
            }
        }
        $success['Pages'] = new PageResource($page);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم إضافة  الصفحة بنجاح', 'page_category Added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show($page)
    {
        $page = Page::query()->find($page);
        if (is_null($page) || $page->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", "Page is't exists");
        }
        $success['pages'] = new PageResource($page);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم عرض الصفحة بنجاح', 'Page showed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $page)
    {
        $page = Page::query()->find($page);
        if (is_null($page) || $page->is_deleted != 0) {

            return $this->sendError("الصفحة غير موجودة", "Page is't exists");
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|string|max:26',
            'page_desc' => 'required',
            'page_content' => 'required',
            'seo_title' => 'nullable',
            'seo_link' => 'nullable',
            'seo_desc' => 'nullable',
            'tags' => 'nullable',
            'altImage' => 'nullable',
            'pageCategory' => ['required', 'array'],
            'pageCategory.*' => 'required',

        ]);
        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors());
        }
        $validator2 = Validator::make($input, [
            'postcategory_id' => Rule::requiredIf(function () {
                return in_array('1', request('pageCategory'));
            }),


        ]);
        if ($validator2->fails()) {
            return $this->sendError(null, $validator2->errors());
        }
        $page->update([
            'title' => $request->input('title'),
            'page_content' => $request->input('page_content'),
            'page_desc' => $request->input('page_desc'),
            'seo_title' => $request->input('seo_title'),
            'seo_link' => $request->input('seo_link'),
            'seo_desc' => $request->input('seo_desc'),
            'tags' => $request->tags,

        ]);

        if ($request->pageCategory) {
            $page->page_categories()->sync($request->pageCategory);
            if (in_array(1, $request->pageCategory)) {

                $page->update([
                    'postcategory_id' => $request->postcategory_id,
                    'altImage' => $request->altImage,
                ]);
                if ($request->has('image')) {
                    $page->update([
                        'image' => $request->image
                    ]);
                }
            } else {

                $page->update([
                    'image' => null,
                    'postcategory_id' => null,
                ]);
            }
        }
        $success['pages'] = new PageResource($page);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم التعديل بنجاح', 'page updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($page)
    {
        $page = Page::query()->find($page);
        if (is_null($page) || $page->is_deleted != 0) {
            return $this->sendError("الصفحة غير موجودة", "page is't exists");
        }
        $page->update(['is_deleted' => $page->id]);

        $success['pages'] = new PageResource($page);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم حذف الصفحة بنجاح', 'page deleted successfully');
    }

    public function changeStatus($id)
    {
        $page = Page::query()->find($id);
        if (is_null($page) || $page->is_deleted != 0) {
            return $this->sendError("  الصفحة غير موجودة", "page is't exists");
        }

        if ($page->status === 'active') {
            $page->update(['status' => 'not_active']);
        } else {
            $page->update(['status' => 'active']);
        }
        $success['pages'] = new PageResource($page);
        $success['status'] = 200;

        return $this->sendResponse($success, 'تم تعديل حالة الصفحة بنجاح', 'page updated successfully');
    }

    public function deleteAll(Request $request)
    {

        $pages = Page::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', null)->get();
        if (count($pages) > 0) {
            foreach ($pages as $page) {

                $page->update(['is_deleted' => $page->id]);
                $success['pages'] = new PageResource($page);

            }

            $success['status'] = 200;
            return $this->sendResponse($success, 'تم حذف الصفحة بنجاح', 'page deleted successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }
    public function changeSatusAll(Request $request)
    {

        $pages = Page::whereIn('id', $request->id)->where('is_deleted', 0)->where('store_id', null)->get();
        if (count($pages) > 0) {
            foreach ($pages as $page) {

                if ($page->status === 'active') {
                    $page->update(['status' => 'not_active']);
                } else {
                    $page->update(['status' => 'active']);
                }
                $success['pages'] = new PageResource($page);

            }
            $success['status'] = 200;

            return $this->sendResponse($success, 'تم تعديل حالة الصفحة بنجاح', 'page updated successfully');
        } else {
            $success['status'] = 200;
            return $this->sendResponse($success, 'المدخلات غير صحيحة', 'id does not exit');
        }
    }

}
