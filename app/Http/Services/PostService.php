<?php

namespace App\Http\Services;

use App\Models\Post;
use App\Models\Platform;
use App\Models\PostPlatform;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostService
{

    public function index()
    {
        $posts = \App\Models\Post::whereAuthenticatedUser()->orderBy('scheduled_time', 'asc')->get();
        $posts->map(function ($post) {
            $post->date = Carbon::parse($post->scheduled_time)->format('Y-m-d');
            $post->time = Carbon::parse($post->scheduled_time)->format('H:i A');
            return $post;
        });


        return $posts;
    }

    public  function store(array $data)
    {
        try {
            DB::beginTransaction();
            Arr::set($data, 'user_id', auth()->guard('sanctum')->user()->id);
            Arr::set($data, 'status', 'scheduled'); // Default status is scheduled
            $postImage = Arr::get($data, 'image');
            if ($postImage) {
                $postImageUrl = Storage::disk('public')->putFile('posts', $postImage);
                Arr::set($data, 'image_url', Storage::disk('public')->url($postImageUrl));
            }

            $post = Post::create(Arr::except($data, ['platforms','image']));
            $post->platforms()->sync(Arr::get($data, 'platforms', []));
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Failed to create post ' . $e->getMessage());
        }
    }


    public  function update(array $data, Post $post)
    {
        if ($post->isPublished())
            throw new HttpException(403, 'You cannot update a published post');
        try {
            DB::beginTransaction();
            Arr::set($data, 'user_id', auth()->guard('sanctum')->user()->id);
            Arr::set($data, 'status', 'scheduled'); // Default status is scheduled
            $postImage = Arr::get($data, 'image');
            if ($postImage) {
                $postImageUrl = Storage::disk('public')->putFile('posts', $postImage);
                Arr::set($data, 'image_url', Storage::disk('public')->url($postImageUrl));
            }
            $post->update(Arr::except($data, ['platforms','image']));
            $post->platforms()->sync(Arr::get($data, 'platforms', []));
            $post->save();
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Failed to update post ' . $e->getMessage());
        }
    }

    public  function destroy(Post $post)
    {
        if ($post->isPublished())
            throw new HttpException(403, 'You cannot delete a published post');

        try {
            DB::beginTransaction();
            $post->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Internal server error while deleting post ' . $e->getMessage());
        }

    }
}
