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
        $listall = DB::table('videos')->skip($offset)->take($per_page)->get();

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
        $video_id = $request->video_id;
        $quez_id = $request->question_id;
        $quez_data = DB::table('videos')
                        ->leftjoin('video_questions', 'videos.id', '=', 'video_questions.video_id')
                        ->leftjoin('answers', 'video_questions.id', '=', 'answers.questionnaire_id')
                        ->select('question_id', 'answer')
                        ->where('video_id', $video_id)
                        ->where('question_id', $quez_id)
                        ->get();
        return response()->json($quez_data);
    }
}