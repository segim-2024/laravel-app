<?php

namespace App\Services;

use App\DTOs\CreateFileDTO;
use App\Models\File;
use App\Services\Interfaces\FileServiceInterface;
use Illuminate\Support\Facades\Storage;

class FileService implements FileServiceInterface {

    /**
     * @inheritDoc
     */
    public function create(CreateFileDTO $DTO): File
    {
        $fileServerName = $DTO->uuid . '.' . $DTO->file->getClientOriginalExtension();

        $DTO->file->storePubliclyAs(
            $DTO->path,
            $fileServerName,
            's3'
        );

        $file = new File();
        $file->uuid = $DTO->uuid;
        $file->extension = $DTO->file->getClientOriginalExtension();
        $file->server_name = $fileServerName;
        $file->original_name = $DTO->file->getClientOriginalName();
        $file->full_path = Storage::disk('s3')->url($DTO->path.'/'.$fileServerName);
        $file->path = $DTO->path;
        $file->size = $DTO->file->getSize();
        $file->save();
        return $file;
    }

    /**
     * @inheritDoc
     */
    public function delete(File $file): void
    {
        Storage::disk('s3')->delete([
            $file->path
        ]);

        $file->delete();
    }
}
