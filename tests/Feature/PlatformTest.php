<?php

use App\Models\Post;
use App\Models\User;
use App\Models\PostPlatform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);


it('can toggle active status of a post if authorized', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $post = Post::factory()->create(['user_id' => auth()->guard('sanctum')->id(), 'status' => 'scheduled']);
    $postPlatform = PostPlatform::factory()->create(['post_id' => $post->id, 'is_active' => true]);

    // Act
    $response = $this->putJson(route('platforms.toggle-active', ['id' => $postPlatform->id]));
    // Assert
    $response->assertOk();
});


it('can\'t toggle active status of a post if unauthorized', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $unauthorizedUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $unauthorizedUser->id, 'status' => 'scheduled']);
    $postPlatform = PostPlatform::factory()->create(['post_id' => $post->id, 'is_active' => true]);
    // Act
    $response = $this->putJson(route('platforms.toggle-active', ['id' => $postPlatform->id]));
    // Assert
    $response->assertStatus(403);
});


