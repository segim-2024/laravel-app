<?php
namespace App\Repositories\Interfaces;

use App\Models\DoctorFileLesson;

interface DoctorFileLessonRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $uuid
     * @return DoctorFileLesson|null
     */
    public function find(string $uuid): ?DoctorFileLesson;
}
