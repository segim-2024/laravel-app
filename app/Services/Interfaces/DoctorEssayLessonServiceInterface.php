<?php

namespace App\Services\Interfaces;

use App\Models\DoctorEssayLesson;

interface DoctorEssayLessonServiceInterface
{
    /**
     * @param string $uuid
     * @return DoctorEssayLesson|null
     */
    public function find(string $uuid): ?DoctorEssayLesson;

    /**
     * @param string $uuid
     * @return void
     */
    public function delete(string $uuid): void;
}
