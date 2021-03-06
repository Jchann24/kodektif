<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->middleware(
            [
                'auth:sanctum',
                'not.suspended'
            ]
        )->except(['index', 'show']);

        $this->user = authUser();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->get('user_id');
        return PostResource::collection(Post::when($id, function ($q, $id) {
            return $q->where('user_id', $id);
        })->latest()->paginate(30));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $validated = $request->validated();
        $newPost = Arr::except($validated, ['categories']);
        $newCategories = $validated['categories'];

        try {
            DB::beginTransaction();

            $newPost['user_id'] = Auth::id();

            $post = Post::create($newPost);
            $post->categories()->sync($newCategories);

            DB::commit();
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Post\PostUpdateRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $validated = $request->validated();
        $this->authorize('update', $post);

        $updatedPost = Arr::except($validated, ['categories']);
        $newCategories = $validated['categories'];

        try {
            DB::beginTransaction();

            $post->update($updatedPost);
            $post->categories()->sync($newCategories);

            $post->save();

            DB::commit();
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage()
            ], $e->status ?? 500);
        }

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
        return Response::json('', 204);
    }
}
