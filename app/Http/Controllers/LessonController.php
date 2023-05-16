<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\LessonRequest;
use App\Models\Category;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index($categoryId)
    {
        if (empty($categoryId)) {
            return response()->view('errors.404', [], 404);
        }

        $lessons = Lesson::with(['items'])
            ->where('category_id', $categoryId)
            ->where('del_flag', 0)
            ->orderBy('id')
            ->get();

        $completedLessons = [];
        if (Auth::check()) {
            $completedLessons = CompletedLesson::where('user_id', Auth::user()->id)->get()->pluck(['lesson_id'])->toArray();
        }

        $category = Category::find($categoryId);

        if (empty($category)) {
            return redirect()->route('category.index');
        }

        return view('lesson.index', [
            'lessons' => $lessons,
            'category' => $category,
            'completedLessons' => $completedLessons]);
    }

    public function store($categoryId, LessonRequest $request)
    {
        $responseObj = ['success' => false, 'data' => []];

        DB::beginTransaction();

        try {
            // save lesson
            $lesson = Lesson::create([
                'category_id' => $categoryId,
                'user_id' => Auth::user()->id,
                'name' => $request->input('name')
            ]);

            $lesson->save();

            // save items
            $dataPost = $request->all();
            $dataItems = [];
            foreach ($dataPost['source'] as $index => $value) {
                $pathAudio = $fileName = null;
                if ($request->hasFile('audio') && $request->file('audio')[$index]->isValid()) {
                    $fileName = time() . '_' . $request->file('audio')[$index]->getClientOriginalName();
                    $pathAudio = $request->audio[$index]->storeAs(config('app.path_audio'), $fileName);
                }

                $item = Item::create([
                    'category_id' => $categoryId,
                    'lesson_id' => $lesson->id,
                    'text_source' => $value,
                    'text_destination' => isset($dataPost['destination'][$index]) ? $dataPost['destination'][$index] : '',
                    'audio_path' => Str::replace('public/', 'storage/', $pathAudio),
                    'audio_name' => $fileName,
                ]);

                $item->save();
            }

            $responseObj['success'] = true;
            request()->session()->flash('success', config('messages.CREATE_SUCCESS'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());
            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }

    /**
     * @param $categoryId
     * @param LessonRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @data
     *  "id" => "6"
        "name" => "Lesson 1"
        "item_id" => array:2 [
            0 => "4"
            1 => null // new item
        ]
        "source" => array:2 [
            0 => "to need a lot of imagination"
            1 => "2"
        ]
        "destination" => array:2 [
            0 => "cần nhiều sự tưởng tượng"
            1 => "2"
        ]
     */
    public function update($categoryId, LessonRequest $request)
    {
        $responseObj = ['success' => false, 'data' => []];

        $lessonId = $request->id;

        DB::beginTransaction();

        try {
            // save lesson
            $lesson = Lesson::where('id', $lessonId)->update(['name' => $request->name]);

            $dataPost = $request->all();

            // temporary delete all items
            Item::where('lesson_id', $lessonId)->update(['del_flag' => 1]);

            // create items
            foreach ($dataPost['item_id'] as $index => $itemId) {
                if (!empty($itemId)) { // new item
                    continue;
                }

                $pathAudio = $fileName = null;

                if ($request->hasFile('audio')
                    && isset($request->file('audio')[$index])
                    && $request->file('audio')[$index]->isValid()
                ) {
                    $fileName = time() . '_' . $request->file('audio')[$index]->getClientOriginalName();
                    $pathAudio = $request->audio[$index]->storeAs(config('app.path_audio'), $fileName);
                }

                $item = Item::create([
                    'category_id' => $categoryId,
                    'lesson_id' => $lessonId,
                    'text_source' => isset($dataPost['source'][$index]) ? $dataPost['source'][$index] : '',
                    'text_destination' => isset($dataPost['destination'][$index]) ? $dataPost['destination'][$index] : '',
                    'audio_path' => Str::replace('public/', 'storage/', $pathAudio),
                    'audio_name' => $fileName,
                ]);

                $item->save();
            }

            // update items
            $index = 0;
            foreach ($dataPost['item_id'] as $index => $itemId) {
                if (empty($itemId)) { // update item
                    continue;
                }

                $item = Item::find($itemId);

                $item->del_flag = 0;
                $item->text_source = isset($dataPost['source'][$index]) ? $dataPost['source'][$index] : '';
                $item->text_destination = isset($dataPost['destination'][$index]) ? $dataPost['destination'][$index] : '';

                if ($request->hasFile('audio')
                    && isset($request->file('audio')[$index])
                    && $request->file('audio')[$index]->isValid()
                ) {
                    $fileName = time() . '_' . $request->file('audio')[$index]->getClientOriginalName();
                    $pathAudio = $request->audio[$index]->storeAs(config('app.path_audio'), $fileName);

                    $item->audio_name = $fileName;
                    $item->audio_path = Str::replace('public/', 'storage/', $pathAudio);
                }

                $item->save();
            }

            $responseObj['success'] = true;
            request()->session()->flash('success', config('messages.CREATE_SUCCESS'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());
            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }

    public function delete($categoryId, $lessonId)
    {
        $responseObj = ['success' => false, 'data' => []];

        if (empty($lessonId) || empty($categoryId)) {
            return response()->json($responseObj);
        }

        DB::beginTransaction();

        try {
            Lesson::where('id', $lessonId)->update(['del_flag' => 1]);
            Item::where('lesson_id', $lessonId)->update(['del_flag' => 1]);

            $responseObj['success'] = true;

            request()->session()->flash('success', config('messages.DELETE_SUCCESS'));

            DB::commit();

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $responseObj['message'] = $e->getMessage();
            DB::rollBack();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }

    public function show($categoryId, $lessonId)
    {
        $responseObj = ['success' => false, 'data' => []];

        if (empty($lessonId) || empty($categoryId)) {
            return response()->json($responseObj);
        }

        try {
            $lesson = Lesson::where('id', $lessonId)->where('del_flag', 0)->first();
            $items = Item::where('lesson_id', $lessonId)->where('del_flag', 0)->get();

            if (empty($lesson)) {
                return response()->json($responseObj);
            }

            $responseObj['success'] = true;
            $responseObj['data'] = view('lesson._form', ['lesson' => $lesson, 'items' => $items])->render();

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }
}
