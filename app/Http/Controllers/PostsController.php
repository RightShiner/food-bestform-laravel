<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Search food list by keyword
     */
    public function search(Request $request){

        $search = $request->input('search');

        $posts = DB::table('foods')
            ->where('name', 'LIKE', "%{$search}%")
            ->get();

        $cnt = 0;
        foreach ($posts as $post) {
            $posts[$cnt]->image = asset('uploads/food_thumb/'.$post->image);
            $cnt++;
        }

        // Return the search view with the resluts compacted
        return response()->json($posts);
    }

    /**
     * Order from user
     */
    public function order(Request $request)
    {
        $order_data = $request->data;

        $cnt = 0;
        // $order_name = [];
        // $order_crowd = [];
        $order_sum = 0;
        foreach ($order_data as $food_data)
        {
            $food_price = DB::table('foods')
                            ->select('unit')
                            ->where('name', $food_data['name'])
                            ->get();
            $food_price = (int)json_decode($food_price)[0]->unit;
            //calculate total_price
            $order_sum += $food_price * $food_data['crowd'];
        }
        //respond success
        return response()->json([
            'status' => 1,
            // 'total_price' => $order_sum,
        ]);
    }

    /**
     * food config info (ingredient and grams)
     */
    public function config(Request $request)
    {
        $food_names = $request->name;
        $food_config = [];

        foreach ($food_names as $food_name) {
            $food_items = DB::table('foods')
                ->leftJoin('food_config', 'foods.id', '=', 'food_config.food_id')
                ->select('ingredient', 'grams')
                ->where('name', $food_name)
                ->get();

            $food_config[$food_name] = $food_items;
        }

        return response()->json($food_config);
    }

    /**
     * Schedule date and time
     */
    public function schedule(Request $request)
    {
        $time_arr = $request->all();

        //respond success
        return response()->json([
            'status' => 1
        ]);
    }

}