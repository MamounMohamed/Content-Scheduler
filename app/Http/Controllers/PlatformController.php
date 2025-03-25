<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\PostPlatform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PlatformController extends Controller
{
    protected $platformsCacheKey;

    public function __construct()
    {
        $this->platformsCacheKey = 'platforms_user_id_';
    }

    public function index()
    {
        $platforms = Cache::remember($this->platformsCacheKey.auth()->guard('sanctum')->user()->id, 3600, function () {
            return PostPlatform::with(['platform', 'post'])
                ->whereUser(auth()->guard('sanctum')->user())->get();
        });

        return Inertia::render('Settings/Settings', ['platforms' => $platforms]);
    }

    public function toggleActive(string $id)
    {
        Cache::forget($this->platformsCacheKey.auth()->guard('sanctum')->user()->id);
        try {
            DB::beginTransaction();
            $platform = PostPlatform::find($id);
            if (!$platform) {
                return $this->failedResponse('Platform not found', 404);
            }
            $platform->load('post.user');
            if (!$platform->isAuthorized()) {
                return $this->failedResponse('You are not authorized to perform this action', 401);
            }

            $platform->update([
                'is_active' => !$platform->is_active,
            ]);

            if(!$platform->is_active && !$platform->post->isPublished()){
                $platform->post->update([
                    'status' => 'draft',
                ]);
            }
            DB::commit();


            $platform->name = $platform->platform->name;
            return $platform;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failedResponse($e->getMessage(), 500);
        }
    }
}
