<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;

        // Apply global middleware to enforce policies
        $this->authorizeResource(Post::class, 'post');
    }

    private function postsCacheKey()
    {
        return 'all_posts_cache_user_id_' . auth()->guard('sanctum')->user()->id;
    }

    private function platformsCacheKey()
    {
        return 'platforms_cache_pluck_name_id';
    }

    private function postCacheKey($postId)
    {
        return 'posts_cache_find_' . $postId;
    }

    public function index()
    {
        $this->authorize('viewAny', Post::class);
        $posts = cache()->remember($this->postsCacheKey(), 3600, function () {
            return $this->postService->index();
        });

        $platforms = cache()->remember($this->platformsCacheKey(), 3600, function () {
            return \App\Models\Platform::pluck('name', 'id');
        });

        return Inertia::render('Posts/Index', ['posts' => $posts, 'platforms' => $platforms]);
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        return Inertia::render(
            'Posts/Manage',
            [
                'postData' => [],
                'allPlatforms' => \App\Models\Platform::get(),
                'mode' => 'create'
            ]
        );
    }

    public function store(PostRequest $request)
    {
        $this->authorize('create', Post::class);
        try {
            cache()->forget($this->postsCacheKey());
            $post = $this->postService->store($request->validated());
            return $this->successResponse(
                [
                    'post' => PostResource::make($post),
                ],
                201
            );
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 500);
        }
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post = cache()->remember($this->postCacheKey($post->id), 3600, function () use ($post) {
            return $post->load('platforms', 'user');
        });

        return Inertia::render('Posts/View', ['postData' => $post]);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return Inertia::render(
            'Posts/Manage',
            [
                'postData' => $post,
                'allPlatforms' => \App\Models\Platform::all(),
                'mode' => 'edit'
            ]
        );
    }

    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        cache()->forget($this->postsCacheKey());
        cache()->forget($this->postCacheKey($post->id));

        try {
            $post = $this->postService->update($request->validated(),$post);
            return $this->successResponse(
                [
                    'post' => PostResource::make($post),
                ]
            );
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 500);
        }
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        cache()->forget($this->postsCacheKey());
        cache()->forget($this->postCacheKey($post->id));

        try {
            $this->postService->destroy($post);
            return $this->successResponse(
                [
                    'message' => 'Post deleted successfully'
                ]
            );
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage(), 500);
        }
    }
}