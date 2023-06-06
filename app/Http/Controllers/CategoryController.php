<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share('activeNav', 'category');
    }

    public function index()
    {
        if (Auth::check()) {
            $data = Category::with(['user'])
                ->where('del_flag', 0)
                ->where('user_id', Auth::user()->id)
                ->orderBy('language_type, name');

            if (Auth::user()->display_all_categories_flag === config('constant.DISPLAY_ALL_CATEGORIES')) {
                $publicCategories = Category::with(['user'])
                    ->where('del_flag', 0)
                    ->where('user_id', '!=', Auth::user()->id)
                    ->where('is_public', 1)
                    ->where('del_flag', 0)
                    ->orderBy('language_type, name');

                $data = $data->union($publicCategories);
            }

        } else {
            $data = Category::with(['user'])
                ->where('is_public', 1)
                ->where('del_flag', 0)
                ->groupBy('id')
                ->orderBy('user_id')
                ->orderBy('id');
        }


        $data = $data->paginate(config('constant.PER_PAGE'));

        return view('category.index', ['data' => $data]);
    }

    public function delete($id)
    {
        $responseObj = ['success' => false, 'data' => []];

        if (empty($id)) {
            return response()->json($responseObj);
        }

        DB::beginTransaction();

        try {
            Category::where('id', $id)->update(['del_flag' => 1]);
            Lesson::where('category_id', $id)->update(['del_flag' => 1]);
            Item::where('category_id', $id)->update(['del_flag' => 1]);

            $lessonIds = Lesson::where('category_id', $id)->get()->pluck('id')->toArray();
            $itemIds = Item::where('category_id', $id)->get()->pluck('id')->toArray();

            Bookmark::whereIn('item_id', $itemIds)->where('user_id', Auth::user()->id)->delete();
            CompletedLesson::whereIn('lesson_id', $lessonIds)->where('user_id', Auth::user()->id)->delete();

            $responseObj['success'] = true;

            request()->session()->flash('success', config('messages.DELETE_SUCCESS'));

            DB::commit();

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            DB::rollBack();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }

    public function store(CategoryRequest $request)
    {
        // if validate successful => save data
        if (!empty($request->id)) {
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->is_public = $request->is_public;
            $category->language_type = $request->language_type;

            $category->save();
            request()->session()->flash('success', config('messages.UPDATE_SUCCESS'));
        } else {
            $category = Category::create([
                'name' => $request->name,
                'is_public' => $request->is_public ? 1 : 0,
                'user_id' => Auth::user()->id,
                'language_type' => $request->language_type,
            ]);

            $category->save();
            request()->session()->flash('success', config('messages.CREATE_SUCCESS'));
        }


        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $responseObj = ['success' => false, 'data' => []];

        if (empty($id)) {
            return response()->json($responseObj);
        }

        try {
            $data = Category::where('id', $id)->where('del_flag', 0)->first()->toArray();

            $responseObj['success'] = true;
            $responseObj['data'] = $data;

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }
}
