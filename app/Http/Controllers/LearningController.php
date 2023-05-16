<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LearningController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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

        return view('learning.show', [
            'lesson' => $lesson,
            'items' => $items,
            'wasCompleted' => $wasCompleted,
            'bookmarkItemIds' => $bookmarkItemIds,
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
            $lesson = CompletedLesson::where('lesson_id', $lessonId)->first();

            if (empty($lesson)) {
                CompletedLesson::create([
                    'lesson_id' => $lessonId,
                    'user_id' => Auth::user()->id,
                ])->save();

                $responseObj['data']['was_completed'] = true;
            } else {
                CompletedLesson::where('lesson_id', $lessonId)->delete();
            }

            $responseObj['success'] = true;

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
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
            $responseObj['data'] = view('learning._form', [
                'lesson' => $lesson,
                'items' => $items,
                'bookmarkItemIds' => $bookmarkItemIds
            ])->render();

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }
}
