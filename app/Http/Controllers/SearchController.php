<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share('hideSearchBar', true);
    }

    public function result()
    {
        $keyword = request()->keyword;

        if (trim($keyword) === '') {
            return view('search.keyword_invalid');
        }

        $result = DB::table('items')
            ->select(['items.*',
                'lessons.name as lesson_name',
                'users.username as username',
                'categories.name as category_name'])
            ->join('lessons', 'items.lesson_id', '=', 'lessons.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('users', 'categories.user_id', '=', 'users.id')
            ->where('items.del_flag', 0)
            ->Where(function ($query) use ($keyword) {
                $query->where('items.text_source', 'like', '%' . $keyword . '%')
                    ->orWhere('items.text_destination', 'like', '%' . $keyword . '%')
                    ->orWhere('lessons.name', 'like', '%' . $keyword . '%')
                    ->orWhere('categories.name', 'like', '%' . $keyword . '%');
            })
            ->groupBy(['items.id', 'items.lesson_id', 'items.category_id'])
            ->orderBy('items.id')
            ->paginate(10);

        $bookmarkItemIds = [];
        if (Auth::check()) {
            $bookmarkItemIds = Bookmark::select(['item_id'])
                ->where('user_id', Auth::user()->id)
                ->get()
                ->pluck('item_id')
                ->toArray();
        }

        return view('search.result', [
            'data' => $result,
            'keyword' => $keyword,
            'bookmarkItemIds' => $bookmarkItemIds,
        ]);
    }

}
