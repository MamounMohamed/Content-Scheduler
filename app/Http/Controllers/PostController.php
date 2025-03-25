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

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {

        $posts = $this->postService->index();
        $platforms = \App\Models\Platform::pluck('name', 'id');
        $posts->map(function ($post) {
            $post->date = Carbon::parse($post->scheduled_time)->format('Y-m-d');
            $post->time = Carbon::parse($post->scheduled_time)->format('H:i A');
            return $post;
        });
        return Inertia::render('Posts', ['posts' => $posts, 'platforms' => $platforms]);
    }

    public function create()
    {
        return Inertia::render(
            'PostEditor/PostEditor',
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
        return Inertia::render(
            'PostEditor/PostEditor',
            [
                'postData' => $this->postService->find($id),
                'allPlatforms' => \App\Models\Platform::all(),
                'mode' => 'edit'
            ]
        );
    }
    public function update(PostRequest $request , string $id)
    {
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
            $post = $this->postService->find($id);
            return $this->successResponse(
                [
                    'post' => PostResource::make($post),
                ]
            );
        } catch (HttpException $e) {
            return $this->failedResponse($e->getMessage(), $e->getStatusCode());
        }
    }

    public function destroy(Request $request, string $id)
    {
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
