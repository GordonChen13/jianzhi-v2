<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Model\Employer;
use App\Model\WorkReviewKeywords;
use App\Http\Controllers\Controller;

class ReviewKeywordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->employer_id)) {
            if (!$employer = Employer::find($request->employer_id)) {
                return response()->json(['status' => 0,'msg' => '找不到该雇主']);
            }
            $keywords = collect();
            $reviews = $employer->reviews()->get();
            foreach ($reviews as $review) {
                $keyword = $review->keywords()->get()->pluck('value');
                $keywords = $keywords->merge($keyword);
            }
            $keywords = array_count_values($keywords->take(10)->toArray());
            arsort($keywords);
            return response()->json(['status' => 1,'keywords' => $keywords]);
        }
        if (isset($request->type) && isset($request->search)) {
            $keywords = WorkReviewKeywords::where('type',$request->type)->where('value','like','%'.$request->search.'%')->get();
        } else if (isset($request->search)) {
            $keywords = WorkReviewKeywords::where('value','like','%'.$request->search.'%')->get();
        }else if (isset($request->type)) {
            $keywords = WorkReviewKeywords::where('type',$request->type)->get();
        } else {
            $keywords = WorkReviewKeywords::all();
        }
        return response()->json(['status' => 1,'keywords' => $keywords]);
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
}
