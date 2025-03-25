<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

it('can create a post for authenticated user', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $platforms = Platform::factory()->count(3)->create();
    $platformIds = $platforms->pluck('id')->toArray();
    $postData = [
        'title' => 'Test Post',
        'content' => 'Test content',
        'scheduled_time' => '2023-01-01 12:00:00',
        'platforms' => $platformIds,
    ];

    // Act
    $response = $this->postJson(route('posts.store'), $postData);

    // Assert
    $response->assertCreated();
    assertDatabaseHas('posts', ['id' => $response->json('data.post.id')]);
});

it('can update a post for authenticated user', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $post = Post::factory()->create(['user_id' => auth()->guard('sanctum')->id(),'status' => 'scheduled']);
    $platforms = Platform::factory()->count(3)->create();
    $platformIds = $platforms->pluck('id')->toArray();
    $postData = [
        'title' => 'Updated Test Post',
        'content' => 'Updated test content',
        'scheduled_time' => '2023-01-01 12:00:00',
        'platforms' => $platformIds,
    ];

    // Act
    $response = $this->putJson(route('posts.update', ['post' => $post->id]), $postData);

    // Assert
    $response->assertOk();
    assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Test Post']);
});

it('can destroy a post for authenticated user', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $post = Post::factory()->create([
        'user_id' => auth()->guard('sanctum')->id(),
        'status' => 'scheduled'
    ]);

    // Act
    $response = $this->delete(route('posts.destroy', ['post' => $post->id]));

    // Assert
    $response->assertOk();
    assertDatabaseMissing('posts', ['id' => $post->id]);
});


it('can\'t destroy a post for unauthorized user', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $post = Post::factory()->create(['user_id' => User::factory()->create()->id, 'status' => 'scheduled']);
    $response = $this->delete(route('posts.destroy', ['post' => $post->id]));
    $response->assertStatus(403);
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
it('can\'t update a post for unauthorized user', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create());
    $post = Post::factory()->create(['user_id' => User::factory()->create()->id, 'status' => 'scheduled']);
    $postData = [
        'title' => 'Updated Test Post',
        'content' => 'Updated test content',
        'scheduled_time' => '2023-01-01 12:00:00',
        'platforms' => [1, 2, 3],
    ];
    $response = $this->putJson(route('posts.update', ['post' => $post->id]), $postData);
    $response->assertStatus(403);
    $this->assertDatabaseHas('posts', ['id' => $post->id , 'title' => $post->title]);
});


