<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($id)
    {
        //
        $posts = User::find($id)->posts()->latest()->paginate(10);
        return PostResource::collection($posts);

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
     * @param PostRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id, PostRequest $request)
    {
        //
        User::find($id)->posts()->create($request->only(['title', 'body']));
        return response()->json(['message' => 'post created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        return response()->json(new PostResource(Post::find($id)));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param $userId
     * @param $postId
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $userId, $postId)
    {
        //
        Post::find($postId)->update($request->only(['title', 'body']));
        return response()->json(['message' => 'post updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId, $id)
    {
        //
        Post::destroy($id);
        return response()->json(['message' => 'post updated successfully'], 200);
    }
}
