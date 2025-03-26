<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class UpdatePostStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $posts = Post::whereScheduledAndPastScheduledTime()->get();
            DB::beginTransaction();

            foreach ($posts as $post) {
                $post->update([
                    'status' => 'published',
                ]);
                Log::info('Updating post status for post ID: ' . $post->id);
            }
            $unpublishedPosts = Post::wherePublishedAndFutureScheduledTime()->get();
            foreach ($unpublishedPosts as $post) {
                $post->update([
                    'status' => 'scheduled',
                ]);
                Log::info('Updating post status for post ID: ' . $post->id);
            }
            DB::commit();
            Log::info('Posts\' status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update posts\' status: ' . $e->getMessage());
        }

        Cache::flush();
    }
}
