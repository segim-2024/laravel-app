<?php
namespace App\Repositories\Eloquent;

use App\Models\DoctorEssayLesson;
use App\Repositories\Interfaces\DoctorEssayLessonRepositoryInterface;

class DoctorEssayLessonRepository extends BaseRepository implements DoctorEssayLessonRepositoryInterface
{
    public function __construct(DoctorEssayLesson $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorEssayLesson
    {
        return DoctorEssayLesson::where('lesson_uuid', '=', $uuid)
            ->with([
                'materials' => ['file']
            ])
            ->first();
    }
}
