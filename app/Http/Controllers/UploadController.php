<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function process(Request $request): Response
    {
        if (! $request->hasFile('file')) return response('No file uploaded', 422);

        $file = $request->file('file');

        if (! $file->isValid()) return response('Upload failed', 422);

        $path = $file->storeAs($file->getClientOriginalName());

        if (! $path) return response('Failed to store file', 500);

        return response($path, 200)->header('Content-Type', 'text/plain');
    }

    public function revert(Request $request): Response
    {
        $path = $request->input('path');

        if (! $path) return response()->noContent();

        Storage::delete($path);

        return response()->noContent();
    }
}
