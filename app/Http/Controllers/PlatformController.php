<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Resources\PostPlatformResource;
use App\Models\PostPlatform;
use App\Http\Services\PlatformService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PlatformController extends Controller
{
    protected $platformService;

    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
        // Apply global middleware to enforce policies
        $this->authorizeResource(PostPlatform::class, 'postPlatform');
    }

    private function platformsCacheKey()
    {
        return 'platforms_user_id_' . auth()->guard('sanctum')->user()->id;
    }

    public function index()
    {
        $this->authorize('viewAny', PostPlatform::class);

        $platforms = Cache::remember($this->platformsCacheKey(), 3600, function () {
            return $this->platformService->index();
        });


        return Inertia::render('Platforms/Index', ['platforms' => $platforms]);
    }

    public function toggleActive(PostPlatform $platform)
    {
        $this->authorize('update', $platform);
        Cache::forget($this->platformsCacheKey());
        try {
            $platform = $this->platformService->toggleActive($platform);
            return $this->successResponse(
                [
                    'platform' => PostPlatformResource::make($platform),
                ]
            );
        } catch (HttpException $e) {
            return $this->failedResponse($e->getMessage(), $e->getStatusCode());
        }
    }
}
