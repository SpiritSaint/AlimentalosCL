<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use function Ramsey\Uuid\v4;

class UploadsRepository
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public static function make(UploadedFile $file): string
    {
        $path = v4() . ".{$file->extension()}";
        $file->move(public_path('images'), $path);
        return $path;
    }
}
