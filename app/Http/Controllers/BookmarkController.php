<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BookmarkController extends Controller
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

        return view('bookmark.show');
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
        $items = Item::whereIn('id', $bookmarkItemIds)->get();

        $displayType = !empty(request()->displayType) ? request()->displayType : 'random';

        $items = $this->randomActive($items->toArray(), $displayType, config('constant.PER_PAGE'));

        view()->share('items', $items);
        view()->share('bookmarkItemIds', $bookmarkItemIds);
    }

    public function store($itemId)
    {
        $responseObj = ['success' => false, 'data' => []];

        try {
            $bookmark = Bookmark::where('item_id', $itemId)->first();

            if (empty($bookmark)) {
                Bookmark::create([
                    'item_id' => $itemId,
                    'user_id' => Auth::user()->id,
                ])->save();

                $responseObj['data']['is_bookmark'] = true;
            } else {
                Bookmark::where('item_id', $itemId)
                    ->where('user_id', Auth::user()->id)
                    ->delete();

                $responseObj['data']['is_bookmark'] = false;
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
}
