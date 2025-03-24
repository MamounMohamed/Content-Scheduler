<?php

namespace App\Http\Services;

use App\Models\Post;
use App\Models\Platform;
use App\Models\PostPlatform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostService
{

    public  function index(){
        $posts = \App\Models\Post::where('user_id', auth()->guard('sanctum')->user()->id)->get();
        return $posts->values();
    }

    public  function store(array $data)
    {
        try {
            DB::beginTransaction();
            Arr::set($data, 'user_id', auth()->guard('sanctum')->user()->id);
            $post = Post::create(Arr::except($data, ['platforms']));
            $post->platforms()->sync(Arr::get($data, 'platforms', []));
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Failed to create post');
        }
    }

    public  function find(string $id){


        $post = Post::find($id);
        if(!$post)
            throw new HttpException(404, 'Post not found');

        if ($post->user_id != auth()->guard('sanctum')->user()->id)
            throw new HttpException(401, 'You are not authorized to perform this action');
        
            return $post;
    }
    

    public  function update(array $data, string $id)
    {
        $post = $this->find($id);
        if($post->isPublished())
            throw new HttpException(401, 'You cannot update a published post');

        try {
            DB::beginTransaction();
            $post->update(Arr::except($data, ['platforms']));
            $post->platforms()->sync(Arr::get($data, 'platforms', []));
            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Failed to update post');
        }
    }


}
