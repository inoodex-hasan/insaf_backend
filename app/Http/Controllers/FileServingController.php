<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileServingController extends Controller
{
    public function preview($path)
    {
        $path = $this->sanitizePath($path);

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $name = basename($path);
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $fileUrl = route('serve-file', ['path' => $path]);
        $downloadUrl = route('download-file', ['path' => $path]);

        return view('admin.files.preview', compact('name', 'extension', 'fileUrl', 'downloadUrl'));
    }

    public function serveFile($path)
    {
        $path = $this->sanitizePath($path);

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        $fullPath = Storage::disk('public')->path($path);
        $mimeType = $this->resolveMimeType($path);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function download($path)
    {
        $path = $this->sanitizePath($path);

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($path, basename($path));
    }

    private function sanitizePath(string $path): string
    {
        return str_replace(['..', '~'], '', $path);
    }

    private function resolveMimeType(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => Storage::disk('public')->mimeType($path) ?: 'application/octet-stream',
        };
    }
}
