<?php
namespace App\Repositories\Eloquent;

use App\Models\DoctorFileLesson;
use App\Repositories\Interfaces\DoctorFileLessonRepositoryInterface;

class DoctorFileLessonRepository extends BaseRepository implements DoctorFileLessonRepositoryInterface
{
    public function __construct(DoctorFileLesson $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileLesson
    {
        return DoctorFileLesson::where('lesson_uuid', '=', $uuid)
            ->with([
                'materials' => [
                    'file'
                ],
                'zip'
            ])
            ->first();
    }
}
