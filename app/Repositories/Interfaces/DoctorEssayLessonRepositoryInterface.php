<?php
namespace App\Repositories\Interfaces;

use App\Models\DoctorEssayLesson;

interface DoctorEssayLessonRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param string $uuid
     * @return DoctorEssayLesson|null
     */
    public function find(string $uuid): ?DoctorEssayLesson;
}
