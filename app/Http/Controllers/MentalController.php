<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

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
            $listall[$cnt]->video_src = asset('uploads/mental_videos/'.$list->video_src);
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