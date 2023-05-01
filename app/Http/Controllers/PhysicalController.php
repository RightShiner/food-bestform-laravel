<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhysicalController extends Controller
{
    /**
     * Get video data
     */
    public function listall(Request $request)
    {
        // get video list per page
        $page_num = $request->page_num;
        $per_page = 4;
        $offset = ($page_num - 1) * $per_page;
        $listall = DB::table('physical_video')->skip($offset)->take($per_page)->get();

        //rename video source url
        $cnt = 0;
        foreach ($listall as $list) {
            $listall[$cnt]->video_src = asset('uploads/physical_videos/'.$list->video_src);
            $cnt++;
        }
        return response()->json($listall);
    }

    /**
     * Get question and answer data about video
     */
    public function question(Request $request)
    {
        $quez_id = $request->id;
        $quez_data = DB::table('physical_video')
                        ->leftjoin('physical_question', 'physical_video.id', '=', 'physical_question.video_id')
                        ->leftjoin('physical_answer', 'physical_question.id', '=', 'physical_answer.quez_id')
                        ->select('quez_id', 'quez', 'answer')
                        ->where('quez_id', $quez_id)
                        ->get();
        return response()->json($quez_data);
    }
}