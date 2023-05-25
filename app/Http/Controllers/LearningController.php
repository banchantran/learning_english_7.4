<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class LearningController extends Controller
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

    public function show($lessonId)
    {
        $lesson = Lesson::with(['items'])->where('id', $lessonId)->where('del_flag', 0)->first();
        $bookmarkItemIds = Bookmark::select(['item_id'])->get()->pluck('item_id')->toArray();

        $wasCompleted = false;
        if (Auth::check()) {
            $wasCompleted = !empty(CompletedLesson::where('lesson_id', $lessonId)
                ->where('user_id', Auth::user()->id)->first());
        }

        if (empty($lesson)) {
            return response()->view('errors.404', [], 404);
        }

        $previousLesson = Lesson::where('id', '<', $lesson->id)
            ->where('category_id', $lesson->category_id)
            ->where('del_flag', 0)
            ->orderBy('id', 'desc')->first();
        $nextLesson = Lesson::where('id', '>', $lesson->id)
            ->where('category_id', $lesson->category_id)
            ->where('del_flag', 0)
            ->orderBy('id', 'asc')->first();

        $items = $this->randomActive($lesson->items->toArray());

        $category = Category::where('id', $lesson->category_id)->where('del_flag', 0)->first();

        return view('learning.show', [
            'category' => $category,
            'lesson' => $lesson,
            'items' => $items,
            'wasCompleted' => $wasCompleted,
            'bookmarkItemIds' => $bookmarkItemIds,
            'totalItems' => count($lesson->items->toArray()),
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
        ]);
    }

    public function markCompleted($lessonId)
    {
        $responseObj = ['success' => false, 'data' => ['was_completed' => false]];

        if (empty($lessonId)) {
            return response()->json($responseObj);
        }

        try {
            $lesson = CompletedLesson::where('lesson_id', $lessonId)
                ->where('user_id', Auth::user()->id)
                ->first();

            if (empty($lesson)) {
                CompletedLesson::create([
                    'lesson_id' => $lessonId,
                    'user_id' => Auth::user()->id,
                    'finished_date' => date('Y-m-d'),
                ])->save();

                $responseObj['data']['was_completed'] = true;
            } else {
                CompletedLesson::where('lesson_id', $lessonId)
                    ->where('user_id', Auth::user()->id)
                    ->delete();
            }

            $responseObj['success'] = true;

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }

    public function reload($lessonId)
    {
        $responseObj = ['success' => false, 'data' => []];

        if (empty($lessonId)) {
            return response()->json($responseObj);
        }

        $displayType = request()->displayType;

        try {
            $lesson = Lesson::with(['items'])->where('id', $lessonId)->first();
            $bookmarkItemIds = Bookmark::select(['item_id'])->get()->pluck('item_id')->toArray();

            $items = $this->randomActive($lesson->items->toArray(), $displayType);

            $responseObj['success'] = true;
            $responseObj['data'] = view($displayType == 'learn_listening' ? 'learning._form_listening' : 'learning._form', [
                'lesson' => $lesson,
                'items' => $items,
                'totalItems' => count($items),
                'bookmarkItemIds' => $bookmarkItemIds,
            ])->render();

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
