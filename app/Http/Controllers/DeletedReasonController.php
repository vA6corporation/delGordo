<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeletedReason;

class DeletedReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deletedReasons = DeletedReason::paginate(18);
        return [
            'deletedReasons' => $deletedReasons->items(),
            'count' => $deletedReasons->total(),
            'pages' => $deletedReasons->lastPage(),
        ];
    }

    public function all() {
        $deletedReasons = DeletedReason::all();
        return ['deletedReasons' => $deletedReasons];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deletedReason = new DeletedReason($request->deletedReason);
        $deletedReason->save();
        return ['deletedReason' => $deletedReason];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deletedReason = DeletedReason::find($id);
        return ['deletedReason' => $deletedReason];
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
        $deletedReason = DeletedReason::find($id);
        $deletedReason->fill($request->deletedReason);
        $deletedReason->save();
        return ['deletedReason' => $deletedReason];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedReason = DeletedReason::find($id);
        $deletedReason->delete();
    }
}
