<?php

namespace App\Http\Services;

use App\Models\Post;
use App\Models\PostPlatform;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
class PlatformService
{

    public function index()
    {
        $postPlatforms = PostPlatform::with(['platform', 'post'])
            ->whereAuthenticatedUser()->get();

        $postPlatforms->map(function ($postPlatform) {
            $postPlatform->name = $postPlatform->platform->name;
            $postPlatform->post_title = $postPlatform->post->title;
            return $postPlatform;
        });

        return $postPlatforms->values();
    }

    public function toggleActive(PostPlatform $platform)
    {
        if (!$platform) {
            throw new HttpException(404, 'Platform not found');
        }
        try {
            DB::beginTransaction();
            $platform->update([
                'is_active' => !$platform->is_active,
            ]);


            if (!($platform->is_active || $platform->post->isPublished())) {
                $platform->post->update([
                    'status' => 'draft',
                ]);
            }
            DB::commit();
            $platform->name = $platform->platform->name;
            return $platform;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new HttpException(500, 'Failed to update platform ' . $e->getMessage());
        }
    }
}
