<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Events\CommentPosted;
use App\Http\Requests\Api\Comment\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $comments = Comment::where('post_id', $id)->latest()->get();
        return response()->json(CommentResource::collection($comments), 200);
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
     * @param $id
     * @param CommentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, CommentRequest $request)
    {
        //
        $request->merge(['post_id' => $id]);
        $comment = Comment::create($request->all());
        broadcast(new CommentPosted(new CommentResource($comment)))->toOthers();
//        broadcast(new CommentPosted($comment));
        return response()->json(['message' => 'Comment Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
