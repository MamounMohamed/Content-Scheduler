<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Services\PostService;
use App\Http\Requests\PostRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
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

    public function index(Request $request)
    {
        $posts = Cache::remember($this->postsCacheKey(), 3600, function () {
            return $this->postService->index();
        });

        $platforms = Cache::remember($this->platformsCacheKey(), 3600, function () {
            return \App\Models\Platform::pluck('name', 'id');
        });


        return Inertia::render('Posts/Index', ['posts' => $posts, 'platforms' => $platforms]);
    }

    public function create()
    {
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
        try {

            Cache::forget($this->postsCacheKey());
            $post = $this->postService->store($request->validated());
            return $this->successResponse(
                [
                    'post' => PostResource::make($post),
                ],
                201
            );
        } catch (HttpException $e) {
            return $this->failedResponse($e->getMessage(), $e->getStatusCode());
        }
    }

    public function edit(string $id)
    {
        try {
            $post = $this->postService->find($id);
            return Inertia::render(
                'Posts/Manage',
                [
                    'postData' => $post,
                    'allPlatforms' => \App\Models\Platform::all(),
                    'mode' => 'edit'
                ]
            );
        } catch (HttpException $e) {
            return Inertia::render($e->getStatusCode() === 404 ? 'Errors/NotFound' : 'Errors/Unauthorized', [
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ])->toResponse(request())->setStatusCode($e->getStatusCode());
        }
    }
    public function update(PostRequest $request, string $id)
    {
        Cache::forget($this->postsCacheKey());
        Cache::forget($this->postCacheKey($id));

        try {
            $post = $this->postService->update($request->validated(), $id);
            return $this->successResponse(
                [
                    'post' => PostResource::make($post),
                ]
            );
        } catch (HttpException $e) {
            return $this->failedResponse($e->getMessage(), $e->getStatusCode());
        }
    }


    public function show(string $id)
    {
        try {
            $post = Cache::remember($this->postCacheKey($id), 3600, function () use ($id) {
                return $this->postService->find($id)->load('platforms', 'user');
            });

            return Inertia::render('Posts/View', ['postData' => $post]);
        } catch (HttpException $e) {
            return Inertia::render($e->getStatusCode() === 404 ? 'Errors/NotFound' : 'Errors/Unauthorized', [
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ])->toResponse(request())->setStatusCode($e->getStatusCode());
        }
    }

    public function destroy(Request $request, string $id)
    {
        Cache::forget($this->postsCacheKey());
        Cache::forget($this->postCacheKey($id));
        try {
            $this->postService->destroy($id);
            return $this->successResponse(
                [
                    'message' => 'Post deleted successfully'
                ]
            );
        } catch (HttpException $e) {
            return $this->failedResponse($e->getMessage(), $e->getStatusCode());
        }
    }
}
