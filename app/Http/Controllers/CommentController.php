<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'string',

        ]);
        $comment = comment::create([
            $data
        ]);
        return response()->json([
            'message' => 'کامنت  با موفقیت روی تسک گذاشته شد',
            'data' => $comment,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, comment $comment)
    {
        $data = $request->validate($data);
        $comment->update($data);

        return response()->json([
        'message'=>'کامنت با موفقیت اپدیت شد',
        'data'=>$comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comments=comment::findOrFail($id);
        $comments->delete();
    }
}
