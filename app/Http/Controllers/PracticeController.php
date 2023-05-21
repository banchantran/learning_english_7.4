<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PracticeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share('activeNav', 'practice');
    }

    public function learn()
    {
        $this->_getData();

        return view('practice.show');
    }

    public function reload()
    {
        $responseObj = ['success' => false, 'data' => []];

        $displayType = request()->input('displayType', 'random');

        try {
            $this->_getData();

            $responseObj['success'] = true;
            $responseObj['data'] = view($displayType == 'learn_listening' ? 'learning._form_listening' : 'learning._form')->render();

            return response()->json($responseObj);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $responseObj['message'] = $e->getMessage();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        return response()->json($responseObj);
    }

    private function _getData()
    {
        $displayType = request()->input('displayType', 'random');
        $rangeTime = request()->input('rangeTime', config('constant.PRACTICE_3_RECENTLY'));
        $perPage = request()->input('perPage', config('constant.PER_PAGE'));

        $bookmarkItemIds = Bookmark::select(['item_id'])->get()->pluck('item_id')->toArray();

        switch (true) {
            case $rangeTime == config('constant.PRACTICE_ALL'):
                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->get()
                    ->pluck('lesson_id')
                    ->toArray();
                break;
            case $rangeTime == config('constant.PRACTICE_THIS_WEEK'):
                $fromDate = date('Y-m-d', strtotime("monday -1 week"));
                $toDate = date('Y-m-d', strtotime("sunday 0 week"));

                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->where('finished_date', '>=', $fromDate)
                    ->where('finished_date', '<=', $toDate)
                    ->get()->pluck('lesson_id')->toArray();

                break;
            case $rangeTime == config('constant.PRACTICE_THIS_MONTH'):
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-t');

                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->where('finished_date', '>=', $fromDate)
                    ->where('finished_date', '<=', $toDate)
                    ->get()->pluck('lesson_id')->toArray();

                break;
            case $rangeTime == config('constant.PRACTICE_LAST_WEEK'):
                $fromDate = date('Y-m-d', strtotime("monday -2 week"));
                $toDate = date('Y-m-d', strtotime("sunday -1 week"));

                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->where('finished_date', '>=', $fromDate)
                    ->where('finished_date', '<=', $toDate)
                    ->get()->pluck('lesson_id')->toArray();

                break;
            case $rangeTime == config('constant.PRACTICE_LAST_MONTH'):
                $fromDate = date('Y-m-01', strtotime("-1 month"));
                $toDate = date('Y-m-t', strtotime("-1 month"));

                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->where('finished_date', '>=', $fromDate)
                    ->where('finished_date', '<=', $toDate)
                    ->get()->pluck('lesson_id')->toArray();

                break;
            case $rangeTime == config('constant.PRACTICE_3_RECENTLY'):
                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->orderBy('finished_date', 'desc')
                    ->limit(3)
                    ->get()->pluck('lesson_id')->toArray();

                break;
            case $rangeTime == config('constant.PRACTICE_7_RECENTLY'):
                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->orderBy('finished_date', 'desc')
                    ->limit(7)
                    ->get()->pluck('lesson_id')->toArray();

                break;
            case $rangeTime == config('constant.PRACTICE_10_RECENTLY'):
                $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)
                    ->orderBy('finished_date', 'desc')
                    ->limit(10)
                    ->get()->pluck('lesson_id')->toArray();

                break;
        }

        if ($displayType == 'learn_listening') {
            $allItems = Item::whereIn('lesson_id', $lessonIds)
                ->whereNotNulL('audio_path')
                ->where('audio_path', '!=', '')
                ->where('del_flag', 0)
                ->get();
        } else {
            $allItems = Item::whereIn('lesson_id', $lessonIds)->where('del_flag', 0)->get();
        }

        $items = $this->randomActive($allItems->toArray(), $displayType, $perPage);

        view()->share('items', $items);
        view()->share('totalItems', count($allItems));
        view()->share('totalLessons', count($lessonIds));
        view()->share('bookmarkItemIds', $bookmarkItemIds);
    }
}
