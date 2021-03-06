<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostVote\PostVoteStoreRequest;
use App\Http\Requests\Post\PostVote\PostVoteUpdateRequest;
use App\Http\Resources\Post\PostVoteResource;
use App\Models\Post\PostVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PostVoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('not.suspended');
        $this->middleware('throttle:tight-throttle');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Post\PostVote\PostVoteStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostVoteStoreRequest $request)
    {
        $validated = $request->validated();
        $postVote = PostVote::firstOrCreate(
            [
                'user_id' => $validated['user_id'],
                'post_id' => $validated['post_id']
            ],
            [
                'upvote' => $validated['upvote']
            ]
        );

        return (new PostVoteResource($postVote))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostVote\PostVoteUpdateRequest  $request
     * @param  \App\Models\PostVote  $postVote
     * @return \Illuminate\Http\Response
     */
    public function update(PostVoteUpdateRequest $request, PostVote $postVote)
    {
        $validated = $request->validated();
        $this->authorize('update', $postVote);

        $postVote->update($validated);
        $postVote->save();

        return new PostVoteResource($postVote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostVote  $postVote
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostVote $postVote)
    {
        $this->authorize('delete', $postVote);

        $postVote->delete();

        return Response::json('', 204);
    }
}
