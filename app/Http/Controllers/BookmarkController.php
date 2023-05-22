<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BookmarkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share('activeNav', 'bookmark');
    }

    public function learn()
    {
        $this->_getData();

        return view('bookmark.show');
    }

    public function reload()
    {
        $responseObj = ['success' => false, 'data' => []];

        $displayType = !empty(request()->displayType) ? request()->displayType : 'random';

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
        $displayType = !empty(request()->displayType) ? request()->displayType : 'random';
        $perPage = request()->input('perPage', config('constant.PER_PAGE'));

        $bookmarkItemIds = Bookmark::select(['item_id'])->where('user_id', Auth::user()->id)->get()->pluck('item_id')->toArray();

        if ($displayType == 'learn_listening') {
            $allItems = Item::whereIn('id', $bookmarkItemIds)
                ->whereNotNull('audio_path')
                ->where('audio_path', '!=', '')
                ->where('del_flag', 0)
                ->get();
        } else {
            $allItems = Item::whereIn('id', $bookmarkItemIds)->where('del_flag', 0)->get();
        }

        $items = $this->randomActive($allItems->toArray(), $displayType, $perPage);

        view()->share('items', $items);
        view()->share('bookmarkItemIds', $bookmarkItemIds);
        view()->share('totalItems', count($allItems));
    }

    public function store($itemId)
    {
        $responseObj = ['success' => false, 'data' => []];

        try {
            $bookmark = Bookmark::where('item_id', $itemId)->where('user_id', Auth::user()->id)->first();

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
