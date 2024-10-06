<?php

namespace App\Services\Interfaces;

use App\Exceptions\CreateZipException;
use App\Models\DoctorFileLesson;

interface DoctorFileLessonServiceInterface
{
    /**
     * @param string $uuid
     * @return DoctorFileLesson|null
     */
    public function find(string $uuid): ?DoctorFileLesson;

    /**
     * @param DoctorFileLesson $lesson
     * @return void
     */
    public function delete(DoctorFileLesson $lesson): void;

    /**
     * @param DoctorFileLesson $lesson
     * @return void
     * @throws CreateZipException
     */
    public function createZip(DoctorFileLesson $lesson): void;
}
