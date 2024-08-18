<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateFileDTO;
use App\Models\File;

interface FileServiceInterface
{
    /**
     * @param CreateFileDTO $DTO
     * @return File
     */
    public function create(CreateFileDTO $DTO): File;

    /**
     * @param File $file
     * @return void
     */
    public function delete(File $file): void;
}
