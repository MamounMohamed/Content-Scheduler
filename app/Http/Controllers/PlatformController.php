<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlatformController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings', ['platforms' => \App\Models\Platform::all()]);
    }

    public function toggleActive(Request $request, string $id)
    {
        $platform = \App\Models\PostPlatform::find($id);
        $platform->update([
            'is_active' => !$platform->is_active,
        ]);

        return $platform;
    }

}
