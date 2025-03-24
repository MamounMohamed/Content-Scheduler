<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPSTORM_META\map;

Route::get('/posts', function (Request $request) {
    $posts = \App\Models\Post::with('user', 'platforms')->get();
    $statusIsRight = true;
    foreach ($posts as $post)
        $statusIsRight = $statusIsRight && $post->status === $post->effective_status;
    $json_array = $posts->toArray();
    $json_array['statusIsRight'] = $statusIsRight;
    return $json_array;
});

Route::get('/posts/{post}', function (Request $request) {
    $post = \App\Models\Post::find($request->post)->load('user', 'platforms');
    $post->statusIsRight = $post->status === $post->effective_status;
    return $post;
});

Route::get('/platforms', function (Request $request) {
    return \App\Models\Platform::with('posts')->get();
});

Route::get('/platforms/{platform}', function (Request $request) {

    return \App\Models\Platform::find($request->platform)->load('posts');
});

Route::get('/post-platforms', function (Request $request) {
    return \App\Models\PostPlatform::with('post', 'platform')->get();
});

Route::get('/post-platforms/{post_platform}', function (Request $request) {
    return \App\Models\PostPlatform::find($request->post_platform)->load('post', 'platform');
});
