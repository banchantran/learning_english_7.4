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
        View::share('activeNav', 'category');
    }


    public function index(Request $request)
    {
        $categories = Category::where('del_flag', 0)->where('user_id', Auth::user()->id)->get();

        if ($request->isMethod('post')) {
            $url = $request->url;

            $htmlContent = file_get_contents($url);

            $DOM = new \DOMDocument();
            @$DOM->loadHTML($htmlContent);

            $Header = $DOM->getElementsByTagName('th');
            $Detail = $DOM->getElementsByTagName('td');

            //#Get header name of the table
            foreach ($Header as $NodeHeader) {
                $aDataTableHeaderHTML[] = trim($NodeHeader->textContent);
            }
            //print_r($aDataTableHeaderHTML); die();

            //#Get row data/detail table without header name as key
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

            return view('crawl.index', ['categories' => $categories, 'data' => $aDataTableDetailHTML]);
        }

        return view('crawl.index', ['categories' => $categories]);
    }
}
