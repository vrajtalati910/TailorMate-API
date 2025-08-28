<?php

namespace App\Traits;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait FileManager
{
    protected function saveFile($file, string $folder_path, $is_from_web = false, $extension = "png")
    {
        $filename = Str::random(30);

        if (!$is_from_web) {
            $extension = $file->getClientOriginalExtension();
        }

        while (Storage::exists("{$folder_path}/{$filename}.{$extension}")) {
            $filename = Str::random(30);
        }
        if ($is_from_web) {
            Storage::put("{$folder_path}/{$filename}.{$extension}", $file);
        } else {
            Storage::put("{$folder_path}/{$filename}.{$extension}", file_get_contents($file->getRealPath()));
        }
        $path = "{$folder_path}/{$filename}.{$extension}";
        return $path;
    }

    /**
     * Delete any file from system
     *
     * @param string $path - path where the file is stored
     */
    public function deleteFile($path)
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
