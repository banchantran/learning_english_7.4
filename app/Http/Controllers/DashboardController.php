<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share('activeNav', 'dashboard');
    }

    public function index()
    {
        $summaryEnglish = $this->_getDataByLanguageType(config('constant.LANGUAGE_TYPE_ENGLISH'));
        $summaryJapanese = $this->_getDataByLanguageType(config('constant.LANGUAGE_TYPE_JAPANESE'));
        $summaryChinese = $this->_getDataByLanguageType(config('constant.LANGUAGE_TYPE_CHINESE'));

        $result[config('constant.LANGUAGE_TYPE_ENGLISH')]['datasets'] = $summaryEnglish;
        $result[config('constant.LANGUAGE_TYPE_ENGLISH')]['label'] = config('config.language_type')[config('constant.LANGUAGE_TYPE_ENGLISH')];
        $result[config('constant.LANGUAGE_TYPE_ENGLISH')]['borderColor'] = 'rgba(251,188,90)';
        $result[config('constant.LANGUAGE_TYPE_ENGLISH')]['backgroundColor'] = 'rgba(251,188,90)';

        $result[config('constant.LANGUAGE_TYPE_JAPANESE')]['datasets'] = $summaryJapanese;
        $result[config('constant.LANGUAGE_TYPE_JAPANESE')]['label'] = config('config.language_type')[config('constant.LANGUAGE_TYPE_JAPANESE')];
        $result[config('constant.LANGUAGE_TYPE_JAPANESE')]['borderColor'] = 'rgba(247,52,122)';
        $result[config('constant.LANGUAGE_TYPE_JAPANESE')]['backgroundColor'] = 'rgba(247,52,122)';

        $result[config('constant.LANGUAGE_TYPE_CHINESE')]['datasets'] = $summaryChinese;
        $result[config('constant.LANGUAGE_TYPE_CHINESE')]['label'] = config('config.language_type')[config('constant.LANGUAGE_TYPE_CHINESE')];
        $result[config('constant.LANGUAGE_TYPE_CHINESE')]['borderColor'] = 'rgba(64,224,208)';
        $result[config('constant.LANGUAGE_TYPE_CHINESE')]['backgroundColor'] = 'rgba(64,224,208)';


        return view('dashboard.index', ['result' => $result]);
    }

    private function _getDataByLanguageType($languageType): array
    {
        $result = [];

        for ($i = 30; $i > 0; $i--) {
            $iteratorDate = date('Y-m-d', strtotime("- $i days"));
            $date = date('F-d', strtotime("- $i days"));
            $previousDate = date('F-d', strtotime("-1 day", strtotime($date)));

            $lessonCompleted = DB::table('completed_lessons')
                ->select(['completed_lessons.*', 'categories.language_type as language_type'])
                ->join('lessons', 'completed_lessons.lesson_id', '=', 'lessons.id')
                ->join('categories', 'lessons.category_id', '=', 'categories.id')
                ->where('completed_lessons.user_id', Auth::user()->id)
                ->where('completed_lessons.finished_date', $iteratorDate)
                ->where('categories.language_type', $languageType)
                ->get();

            $result[$date] = isset($result[$previousDate]) ? $result[$previousDate] : 0;

            if ($lessonCompleted->isEmpty()) continue;

            $lessonIds = Arr::pluck($lessonCompleted, 'lesson_id');

            $result[$date] += Item::where('del_flag', 0)->where('lesson_id', $lessonIds)->count();
        }

        return $result;
    }

}
