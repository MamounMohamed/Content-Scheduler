<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::whereScheduledAndPastScheduledTime()->get();
        DB::beginTransaction();
        foreach ($posts as $post) {
            $post->update([
                'status' => 'published',
            ]);
        }
        DB::commit();

        $this->info('Post status updated successfully');
    }
}
