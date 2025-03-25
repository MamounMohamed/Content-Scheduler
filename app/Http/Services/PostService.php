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
                Arr::set($data, 'image_url', $postImageUrl);
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

    public  function find(string $id)
    {
        $post = Post::find($id);
        if (!$post)
            throw new HttpException(404, 'Post not found');
        if (!$post->isAuthorized())
            throw new HttpException(401, 'You are not authorized to perform this action');
        return $post;
    }


    public  function update(array $data, string $id)
    {
        $post = $this->find($id);
        if ($post->isPublished())
            throw new HttpException(401, 'You cannot update a published post');

        try {
            DB::beginTransaction();

            Arr::set($data, 'user_id', auth()->guard('sanctum')->user()->id);
            Arr::set($data, 'status', 'scheduled'); // Default status is scheduled
            $postImage = Arr::get($data, 'image');
            if ($postImage) {
                $postImageUrl = Storage::disk('public')->putFile('posts', $postImage->getClientOriginalName(), $postImage);
                Arr::set($data, 'image_url', $postImageUrl);
            }
            $post->update(Arr::except($data, ['platforms','image']));
            $post->platforms()->sync(Arr::get($data, 'platforms', []));
            $post->save();
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Failed to update post');
        }
    }

    public  function destroy(string $id)
    {
        try {
            $post = $this->find($id);
            if ($post->isPublished())
                throw new HttpException(401, 'You cannot delete a published post');
            DB::beginTransaction();
            $post->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Internal server error while deleting post ' . $e->getMessage());
        }
    }
}
