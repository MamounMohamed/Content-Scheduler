<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
class UpdatePostStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update post status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $posts = Post::whereScheduledAndPastScheduledTime()->get();
            DB::beginTransaction();
            foreach ($posts as $post) {
                $post->update([
                    'status' => 'published',
                ]);
                $this->info('Updating post status for post id: ' . $post->id);
            }
            DB::commit();
            $this->info('Posts\' status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to update posts\' status');
        }

        Cache::flush();
        
    }
}
