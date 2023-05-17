<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function learn()
    {
        $this->_getData();

        return view('practice.show');
    }

    public function reload()
    {
        $responseObj = ['success' => false, 'data' => []];

        try {
            $this->_getData();

            $responseObj['success'] = true;
            $responseObj['data'] = view('learning._form')->render();

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
        $bookmarkItemIds = Bookmark::select(['item_id'])->get()->pluck('item_id')->toArray();

        $lessonIds = CompletedLesson::where('user_id', Auth::user()->id)->get()->pluck('lesson_id')->toArray();

        $allItems = Item::whereIn('lesson_id', $lessonIds)->get();

        $displayType = request()->input('displayType', 'random');

        $items = $this->randomActive($allItems->toArray(), $displayType, config('constant.PER_PAGE'));

        view()->share('items', $items);
        view()->share('totalItems', count($allItems));
        view()->share('totalLessons', count($lessonIds));
        view()->share('bookmarkItemIds', $bookmarkItemIds);
    }
}
