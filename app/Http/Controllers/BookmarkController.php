<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    const MAX_ITEM = 10;

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
        $bookmarkItemIds = Bookmark::select(['item_id'])->get()->pluck('item_id')->toArray();
        $items = Item::whereIn('id', $bookmarkItemIds)->get();

        $items = $this->randomActive($items->toArray(), 'random', self::MAX_ITEM);

        return view('bookmark.show', [
            'items' => $items,
            'bookmarkItemIds' => $bookmarkItemIds,
        ]);
    }

    public function reload()
    {
        $responseObj = ['success' => false, 'data' => []];

        $displayType = request()->displayType;

        try {
            $bookmarkItemIds = Bookmark::select(['item_id'])->get()->pluck('item_id')->toArray();

            $items = Item::whereIn('id', $bookmarkItemIds)->get();

            $items = $this->randomActive($items->toArray(), $displayType, self::MAX_ITEM);

            $responseObj['success'] = true;
            $responseObj['data'] = view('learning._form', [
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

    public function store($itemId)
    {
        $responseObj = ['success' => false, 'data' => []];

        try {
            $bookmark = Bookmark::where('item_id', $itemId)->first();

            if (empty($bookmark)) {
                Bookmark::create([
                    'item_id' => $itemId
                ])->save();

                $responseObj['data']['is_bookmark'] = true;
            } else {
                Bookmark::where('item_id', $itemId)->delete();

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
