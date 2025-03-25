<?php

namespace App\Http\Services;

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

    public function toggleActive($id)
    {
        try {
            DB::beginTransaction();
            $platform = PostPlatform::find($id);
            if (!$platform) {
                throw new HttpException(404, 'Platform not found');
            }
            $platform->load('post.user');
            if (!$platform->isAuthorized()) {
                return new HttpException(401, 'You are not authorized to perform this action');
            }

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
            return new HttpException(500, 'Failed to update platform ' . $e->getMessage());
        }
    }
}
