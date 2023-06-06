<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\CompletedLesson;
use App\Models\Item;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CrawlController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        View::share('activeNav', '');
    }

    public function index(Request $request)
    {
        $categories = Category::where('del_flag', 0)->where('user_id', Auth::user()->id)->get();

        return view('crawl.index', [
            'categories' => $categories,
            'crawlCategoryId' => $request->session()->get('crawlCategoryId'),
            'crawlLessonName' => $request->session()->get('crawlLessonName'),
            'crawlUrl' => $request->session()->get('crawlUrl'),
        ]);
    }

    public function kanji(Request $request)
    {
        $categories = Category::where('del_flag', 0)->where('user_id', Auth::user()->id)->get();

        return view('crawl.kanji', [
            'categories' => $categories,
            'crawlCategoryId' => $request->session()->get('crawlCategoryId'),
            'crawlLessonName' => $request->session()->get('crawlLessonName'),
            'crawlUrl' => $request->session()->get('crawlUrl'),
        ]);
    }

    public function crawlKanji(Request $request)
    {
        $responseObj = ['success' => false, 'data' => [], 'message' => ''];

        $url = $request->urlCrawl;
        $fromPosition = $request->fromPosition;
        $toPosition = $request->toPosition;

        $htmlContent = file_get_contents($url);

        $DOM = new \DOMDocument();
        @$DOM->loadHTML($htmlContent);

        $Detail = $DOM->getElementsByTagName('td');

        $i = 0;
        $j = 0;
        foreach ($Detail as $sNodeDetail) {
            $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
            $i = $i + 1;
            $j = $i % 6 == 0 ? $j + 1 : $j;
        }

        unset($aDataTableDetailHTML[0]);

        $aDataTableDetailHTML = array_slice($aDataTableDetailHTML, $fromPosition, $toPosition);

        $responseObj['success'] = true;
        $responseObj['data'] = view('crawl._list_kanji', ['data' => $aDataTableDetailHTML])->render();


        return response()->json($responseObj);
    }

    public function crawl(Request $request)
    {
        $responseObj = ['success' => false, 'data' => [], 'message' => ''];


        $url = $request->urlCrawl;

        $htmlContent = file_get_contents($url);

        $DOM = new \DOMDocument();
        @$DOM->loadHTML($htmlContent);

        $Header = $DOM->getElementsByTagName('th');
        $Detail = $DOM->getElementsByTagName('td');

        //#Get header name of the table
        foreach ($Header as $NodeHeader) {
            $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
        }

        $i = 0;
        $j = 0;
        foreach ($Detail as $sNodeDetail) {
            $link = $sNodeDetail->getElementsByTagName('a');
            if (!empty($link->item(0))) {
                $aDataTableDetailHTML[$j][] = 'https://jls.vnjpclub.com/' . trim($link->item(0)->getAttribute('href'));
            }

            $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
            $i = $i + 1;
            $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
        }

        $responseObj['success'] = true;
        $responseObj['data'] = view('crawl._list', ['data' => $aDataTableDetailHTML])->render();


        return response()->json($responseObj);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $lessonId = DB::table('lessons')->insertGetId([
                'category_id' => $request->category_id,
                'user_id' => Auth::user()->id,
                'name' => $request->lesson
            ]);

            foreach ($request->source as $index => $value) {

                Item::create([
                    'lesson_id' => $lessonId,
                    'category_id' => $request->category_id,
                    'text_source' => !empty($request->source[$index]) ? $request->source[$index] : '',
                    'text_destination' => !empty($request->destination[$index]) ? $request->destination[$index] : '',
                    'text_note' => $request->note[$index],
                    'audio_path' => str_replace('\\', '/', $request->audio_path[$index]),
                    'audio_name' => basename(str_replace('\\', '/', $request->audio_path[$index])),
                    'is_crawl' => 1
                ])->save();

            }

            DB::commit();

            request()->session()->flash('success', config('messages.CREATE_SUCCESS'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            DB::rollBack();

            request()->session()->flash('error', config('messages.SYSTEM_ERROR'));
        }

        $request->session()->put('crawlCategoryId', $request->category_id);
        $request->session()->put('crawlLessonName', $request->lesson);
        $request->session()->put('crawlUrl', $request->url);

        return redirect()->route('crawl.kanji');
    }
}
