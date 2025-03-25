<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\PostPlatform;
use Illuminate\Support\Facades\DB;

class PlatformController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Settings', ['platforms' => PostPlatform::with(['platform', 'post'])
            ->whereHas('post', function ($query) {
                $query->where('user_id', auth()->guard('sanctum')->user()->id);
            })->get()]);
    }

    public function toggleActive(string $id)
    {
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

            DB::commit();
            $platform->name = $platform->platform->name;


            return $platform;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failedResponse($e->getMessage(), 500);
        }
    }
}
